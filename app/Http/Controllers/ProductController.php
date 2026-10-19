<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

/**
 * ProductController
 * Módulo de Productos del ERP "Restaurante Don Julio".
 * Gestiona el CRUD de productos del menú con filtros, búsqueda y paginación.
 */
class ProductController extends Controller
{
    /**
     * Sucursal activa del usuario autenticado (fallback: sede principal id 1).
     */
    private function branchId(): int
    {
        return auth()->user()?->current_branch_id ?? 1;
    }

    /**
     * Listado de productos con filtro por categoría, búsqueda por nombre
     * y toggle de estado activo/inactivo. Paginado de a 10 registros.
     */
    public function index(Request $request): Response
    {
        // Normalizar los parámetros de filtro enviados por query string
        $filters = [
            'search' => (string) $request->query('search', ''),
            'category_id' => $request->query('category_id') ?: '',
            'status' => $request->query('status', ''), // '', 'active', 'inactive'
        ];

        $products = Product::query()
            ->with('category')                                   // Categoría para mostrar su nombre
            ->where('branch_id', $this->branchId())              // Aislar datos por sucursal
            ->search($filters['search'])                         // Búsqueda por nombre/código
            ->byCategory($filters['category_id'])                // Filtro por categoría
            ->when(
                $filters['status'] === 'active',
                fn ($q) => $q->where('is_active', true)
            )
            ->when(
                $filters['status'] === 'inactive',
                fn ($q) => $q->where('is_active', false)
            )
            ->orderBy('code')
            ->paginate(10)
            ->withQueryString();                                 // Mantener filtros en la paginación

        // Categorías disponibles para el dropdown de filtrado
        $categories = Category::query()
            ->where('branch_id', $this->branchId())
            ->orderBy('name')
            ->get(['id', 'name', 'icon']);

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $filters,
        ]);
    }

    /**
     * Formulario de creación de producto.
     */
    public function create(): Response
    {
        $categories = Category::query()
            ->where('branch_id', $this->branchId())
            ->orderBy('name')
            ->get(['id', 'name', 'icon']);

        return Inertia::render('Products/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Almacenar un nuevo producto en la base de datos.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            // Validación realizada por StoreProductRequest
            $data = $request->validated();
            $data['branch_id'] = $this->branchId();

            $product = Product::create($data);

            return redirect()
                ->route('products.index')
                ->with('success', "Producto «{$product->name}» creado correctamente.");
        } catch (Throwable $e) {
            // Log del error y mensaje amigable al usuario
            report($e);

            return back()
                ->withInput()
                ->with('error', 'No se pudo crear el producto. Intente nuevamente.');
        }
    }

    /**
     * Formulario de edición con datos precargados.
     */
    public function edit(Product $product): Response
    {
        // Evitar editar productos de otra sucursal o inexistentes
        abort_unless($product->branch_id === $this->branchId(), 404);

        $categories = Category::query()
            ->where('branch_id', $this->branchId())
            ->orderBy('name')
            ->get(['id', 'name', 'icon']);

        return Inertia::render('Products/Edit', [
            'product' => [
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'category_id' => $product->category_id,
                'base_price' => (float) $product->base_price,
                'cost' => (float) $product->cost,
                'short_description' => $product->short_description,
                'long_description' => $product->long_description,
                'is_active' => $product->is_active,
                'is_visible_web' => $product->is_visible_web,
                'is_visible_pos' => $product->is_visible_pos,
                'includes_igv' => $product->includes_igv,
            ],
            'categories' => $categories,
        ]);
    }

    /**
     * Actualizar un producto existente.
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        abort_unless($product->branch_id === $this->branchId(), 404);

        try {
            $product->update($request->validated());

            return redirect()
                ->route('products.index')
                ->with('success', "Producto «{$product->name}» actualizado correctamente.");
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el producto. Intente nuevamente.');
        }
    }

    /**
     * Borrado lógico (soft delete) del producto.
     */
    public function destroy(Product $product)
    {
        abort_unless($product->branch_id === $this->branchId(), 404);

        try {
            $name = $product->name;
            $product->delete(); // Soft delete según Expediente Técnico

            return redirect()
                ->route('products.index')
                ->with('success', "Producto «{$name}» eliminado correctamente.");
        } catch (Throwable $e) {
            report($e);

            return back()
                ->with('error', 'No se pudo eliminar el producto. Intente nuevamente.');
        }
    }

    /**
     * Alternar el estado activo/inactivo del producto (toggle desde el listado).
     */
    public function toggleStatus(Product $product)
    {
        abort_unless($product->branch_id === $this->branchId(), 404);

        try {
            $product->update(['is_active' => !$product->is_active]);

            return back()->with(
                'success',
                $product->is_active
                    ? "Producto «{$product->name}» activado."
                    : "Producto «{$product->name}» desactivado."
            );
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'No se pudo cambiar el estado del producto.');
        }
    }
}
