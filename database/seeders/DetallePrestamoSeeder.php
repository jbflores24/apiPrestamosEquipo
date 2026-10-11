<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetallePrestamoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('detalle_prestamos')->insert([
            'prestamo_id' => 1,
            'equipo_id' => 1,
            'fecha_devolucion_real' => null,
            'observaciones' => 'Entregada con cargador',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('detalle_prestamos')->insert([
            'prestamo_id' => 1,
            'equipo_id' => 2,
            'fecha_devolucion_real' => null,
            'observaciones' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('detalle_prestamos')->insert([
            'prestamo_id' => 2,
            'equipo_id' => 3,
            'fecha_devolucion_real' => '2026-09-24',
            'observaciones' => 'Devuelta en buen estado',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('detalle_prestamos')->insert([
            'prestamo_id' => 3,
            'equipo_id' => 4,
            'fecha_devolucion_real' => '2026-09-15',
            'observaciones' => 'Devuelta con retraso',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
