<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrestamoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prestamos')->insert([
            'alumno_id' => 1,
            'fecha_salida' => '2026-10-01',
            'fecha_devolucion_esperada' => '2026-10-05',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('prestamos')->insert([
            'alumno_id' => 2,
            'fecha_salida' => '2026-09-20',
            'fecha_devolucion_esperada' => '2026-09-25',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('prestamos')->insert([
            'alumno_id' => 3,
            'fecha_salida' => '2026-09-10',
            'fecha_devolucion_esperada' => '2026-09-12',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
