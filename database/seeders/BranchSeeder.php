<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('branches')->updateOrInsert(
            ['id' => 1],
            [
                'code' => 'DJ-01',
                'name' => 'Don Julio - Sede Principal',
                'trade_name' => 'Restaurante Don Julio',
                'tax_id' => '20100000001',
                'address' => 'Av. La Marina 123, Miraflores',
                'district' => 'Miraflores',
                'province' => 'Lima',
                'department' => 'Lima',
                'phone' => '01 2345678',
                'email' => 'contacto@donjulio.com',
                'schedule' => json_encode(['lun-dom' => '08:00-23:00']),
                'timezone' => 'America/Lima',
                'currency' => 'PEN',
                'igv_rate' => 0.18,
                'is_active' => true,
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
