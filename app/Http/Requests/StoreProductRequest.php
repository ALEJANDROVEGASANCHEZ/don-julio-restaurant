<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * FormRequest StoreProductRequest
 * Valida los datos al crear o actualizar un producto del módulo de Productos.
 * En actualización se excluye el id actual de la regla de unicidad del código.
 */
class StoreProductRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado a realizar la acción.
     * La autorización por roles se maneja vía middleware en las rutas.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación con mensajes en español.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Sucursal del usuario autenticado (fallback: sede principal id 1)
        $branchId = auth()->user()?->current_branch_id ?? 1;

        // En edición, la ruta resuelve el modelo Product (route model binding);
        // se usa el identificador para excluirlo de la regla de unicidad.
        $productRoute = $this->route('product');
        $productId = is_object($productRoute) ? $productRoute->id : $productRoute;

        return [
            'code' => [
                'required',
                'string',
                'max:40',
                // Código único por sucursal (incluye borrados lógicos para evitar duplicados)
                Rule::unique('products', 'code')
                    ->ignore($productId)
                    ->where('branch_id', $branchId),
            ],
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string|max:500',
            'long_description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_visible_web' => 'boolean',
            'is_visible_pos' => 'boolean',
            'includes_igv' => 'boolean',
        ];
    }

    /**
     * Nombres amigables de campos para los mensajes de error.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'code' => 'código',
            'name' => 'nombre',
            'category_id' => 'categoría',
            'base_price' => 'precio de venta',
            'cost' => 'costo',
            'short_description' => 'descripción corta',
            'long_description' => 'descripción larga',
        ];
    }

    /**
     * Mensajes de error personalizados en español.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.unique' => 'Ya existe un producto con este código en la sucursal.',
            'base_price.required' => 'El precio de venta es obligatorio.',
            'base_price.numeric' => 'El precio de venta debe ser un número válido.',
        ];
    }

    /**
     * Normalizar checkboxes no marcados (Inertia envía ausente → false).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_visible_web' => $this->boolean('is_visible_web'),
            'is_visible_pos' => $this->boolean('is_visible_pos'),
            'includes_igv' => $this->boolean('includes_igv'),
            'cost' => $this->input('cost', 0),
        ]);
    }
}
