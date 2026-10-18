<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommercialSeeder extends Seeder
{
    public function run(): void
    {
        // Categorías
        $categorias = [
            ['branch_id' => 1, 'code' => 'CAT-ENT', 'name' => 'Entradas', 'slug' => 'entradas', 'icon' => '🥗', 'is_active' => true, 'created_at' => now()],
            ['branch_id' => 1, 'code' => 'CAT-FON', 'name' => 'Platos de Fondo', 'slug' => 'platos-de-fondo', 'icon' => '🍛', 'is_active' => true, 'created_at' => now()],
            ['branch_id' => 1, 'code' => 'CAT-BEB', 'name' => 'Bebidas', 'slug' => 'bebidas', 'icon' => '🥤', 'is_active' => true, 'created_at' => now()],
            ['branch_id' => 1, 'code' => 'CAT-POS', 'name' => 'Postres', 'slug' => 'postres', 'icon' => '🍰', 'is_active' => true, 'created_at' => now()],
        ];
        foreach ($categorias as $cat) {
            DB::table('categories')->updateOrInsert(['branch_id' => $cat['branch_id'], 'code' => $cat['code']], $cat);
        }

        // Productos
        $productos = [
            ['branch_id' => 1, 'category_id' => 1, 'code' => 'PROD-001', 'name' => 'Ceviche Clásico', 'slug' => 'ceviche-clasico', 'short_description' => 'Pescado fresco marinado en limón con cebolla y ají.', 'base_price' => 38.00, 'cost' => 15.00, 'is_active' => true, 'is_visible_web' => true, 'is_visible_pos' => true, 'created_at' => now()],
            ['branch_id' => 1, 'category_id' => 2, 'code' => 'PROD-002', 'name' => 'Lomo Saltado', 'slug' => 'lomo-saltado', 'short_description' => 'Clásico peruano con papas fritas y arroz.', 'base_price' => 45.00, 'cost' => 18.00, 'is_active' => true, 'is_visible_web' => true, 'is_visible_pos' => true, 'created_at' => now()],
            ['branch_id' => 1, 'category_id' => 2, 'code' => 'PROD-003', 'name' => 'Ají de Gallina', 'slug' => 'aji-de-gallina', 'short_description' => 'Pollo deshilachado en crema de ají amarillo.', 'base_price' => 35.00, 'cost' => 12.00, 'is_active' => true, 'is_visible_web' => true, 'is_visible_pos' => true, 'created_at' => now()],
            ['branch_id' => 1, 'category_id' => 3, 'code' => 'PROD-004', 'name' => 'Chicha Morada 500ml', 'slug' => 'chicha-morada', 'short_description' => 'Bebida tradicional de maíz morado.', 'base_price' => 12.00, 'cost' => 3.00, 'is_active' => true, 'is_visible_web' => true, 'is_visible_pos' => true, 'created_at' => now()],
            ['branch_id' => 1, 'category_id' => 4, 'code' => 'PROD-005', 'name' => 'Suspiro Limeño', 'slug' => 'suspiro-limeno', 'short_description' => 'Dulce tradicional con merengue de oporto.', 'base_price' => 18.00, 'cost' => 6.00, 'is_active' => true, 'is_visible_web' => true, 'is_visible_pos' => true, 'created_at' => now()],
        ];
        foreach ($productos as $prod) {
            DB::table('products')->updateOrInsert(['branch_id' => $prod['branch_id'], 'code' => $prod['code']], $prod);
        }
    }
}
