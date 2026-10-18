<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        // Zonas
        $zonas = [
            ['branch_id' => 1, 'code' => 'Z-01', 'name' => 'Salón Principal', 'type' => 'dining', 'capacity' => 40, 'is_active' => true, 'created_at' => now()],
            ['branch_id' => 1, 'code' => 'Z-02', 'name' => 'Terraza', 'type' => 'terrace', 'capacity' => 20, 'is_active' => true, 'created_at' => now()],
        ];
        foreach ($zonas as $zona) {
            DB::table('zones')->updateOrInsert(['branch_id' => $zona['branch_id'], 'code' => $zona['code']], $zona);
        }

        // Mesas (solo si no existen)
        if (DB::table('tables')->where('branch_id', 1)->count() === 0) {
            $mesas = [];
            for ($i = 1; $i <= 10; $i++) {
                $mesas[] = ['branch_id' => 1, 'zone_id' => 1, 'code' => "M-$i", 'name' => "Mesa $i", 'seats' => 4, 'status' => 'available', 'is_active' => true, 'created_at' => now()];
            }
            DB::table('tables')->insert($mesas);
        }

        // Estaciones de Cocina
        $estaciones = [
            ['branch_id' => 1, 'code' => 'EST-CAL', 'name' => 'Cocina Caliente', 'type' => 'kitchen', 'avg_prep_minutes' => 15, 'is_active' => true, 'created_at' => now()],
            ['branch_id' => 1, 'code' => 'EST-FRI', 'name' => 'Cocina Fría / Entradas', 'type' => 'cold', 'avg_prep_minutes' => 10, 'is_active' => true, 'created_at' => now()],
            ['branch_id' => 1, 'code' => 'EST-BAR', 'name' => 'Barra de Bebidas', 'type' => 'bar', 'avg_prep_minutes' => 5, 'is_active' => true, 'created_at' => now()],
        ];
        foreach ($estaciones as $est) {
            DB::table('stations')->updateOrInsert(['branch_id' => $est['branch_id'], 'code' => $est['code']], $est);
        }

        // Cajas
        $cajas = [
            ['branch_id' => 1, 'code' => 'CAJA-01', 'name' => 'Caja Principal', 'location' => 'Salón', 'is_active' => true, 'created_at' => now()],
            ['branch_id' => 1, 'code' => 'CAJA-02', 'name' => 'Caja Mostrador', 'location' => 'Entrada', 'is_active' => true, 'created_at' => now()],
        ];
        foreach ($cajas as $caja) {
            DB::table('cash_registers')->updateOrInsert(['branch_id' => $caja['branch_id'], 'code' => $caja['code']], $caja);
        }
    }
}
