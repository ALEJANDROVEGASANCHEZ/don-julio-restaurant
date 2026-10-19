<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Branch
 * Representa una sucursal del restaurante (ej. Don Julio - Sede Principal).
 */
class Branch extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Campos asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'trade_name',
        'tax_id',
        'address',
        'district',
        'province',
        'department',
        'phone',
        'email',
        'schedule',
        'timezone',
        'currency',
        'igv_rate',
        'is_active',
        'is_default',
        'settings',
    ];

    /**
     * Casting de atributos a tipos nativos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'schedule' => 'array',
            'settings' => 'array',
            'igv_rate' => 'decimal:4',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    // ─── Relaciones ────────────────────────────────────────────────

    /** Productos de la sucursal. */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /** Categorías de la sucursal. */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
