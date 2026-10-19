<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo ProductVariant
 * Representa una variante de un producto (ej. tamaño, porción) con deltas de precio/costo.
 */
class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Campos asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'code',
        'name',
        'price_delta',
        'cost_delta',
        'sku',
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
            'price_delta' => 'decimal:2',
            'cost_delta' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // ─── Relaciones ────────────────────────────────────────────────

    /** Producto padre de la variante. */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
