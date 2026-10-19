<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Modelo Product
 * Representa un producto del menú (plato, bebida o postre) de una sucursal.
 * Soporta borrado lógico (soft deletes) según el Expediente Técnico.
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Campos asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'category_id',
        'sku',
        'code',
        'name',
        'slug',
        'short_description',
        'long_description',
        'image_path',
        'type',
        'unit',
        'base_price',
        'cost',
        'includes_igv',
        'igv_rate',
        'tax_code',
        'is_active',
        'is_visible_web',
        'is_visible_pos',
        'allow_modifiers',
        'sort_order',
        'allergens',
        'tags',
    ];

    /**
     * Casting de atributos a tipos nativos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'cost' => 'decimal:2',
            'igv_rate' => 'decimal:4',
            'includes_igv' => 'boolean',
            'is_active' => 'boolean',
            'is_visible_web' => 'boolean',
            'is_visible_pos' => 'boolean',
            'allow_modifiers' => 'boolean',
            'allergens' => 'array',
            'tags' => 'array',
        ];
    }

    // ─── Eventos del modelo ────────────────────────────────────────

    /** Registrar booted() para autogenerar el slug al guardar. */
    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            // Autogenerar slug si no viene informado
            if (blank($product->slug) && filled($product->name)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // ─── Relaciones ────────────────────────────────────────────────

    /** Sucursal a la que pertenece el producto. */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /** Categoría del producto. */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Variantes del producto (tamaños, presentaciones). */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    // ─── Scopes de consulta ────────────────────────────────────────

    /** Filtrar por término de búsqueda (nombre o código). */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('code', 'like', "%{$term}%");
        });
    }

    /** Filtrar por categoría. */
    public function scopeByCategory(Builder $query, mixed $categoryId): Builder
    {
        if (blank($categoryId)) {
            return $query;
        }

        return $query->where('category_id', $categoryId);
    }

    /** Solo productos activos. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
