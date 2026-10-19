<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Category
 * Representa una categoría de productos del menú (Entradas, Platos de Fondo, Bebidas, Postres).
 */
class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Campos asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'parent_id',
        'code',
        'name',
        'slug',
        'description',
        'icon',
        'image_path',
        'display_channel',
        'sort_order',
        'is_active',
    ];

    /**
     * Casting de atributos a tipos nativos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ─── Relaciones ────────────────────────────────────────────────

    /** Sucursal a la que pertenece la categoría. */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /** Categoría padre (jerarquía opcional). */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /** Subcategorías. */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /** Productos de esta categoría. */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
