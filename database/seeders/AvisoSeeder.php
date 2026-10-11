<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('avisos')->insert([
            'titulo' => 'Inventario de equipos',
            'texto' => 'Se realizará el inventario general el próximo lunes.',
            'fecha' => '2026-10-05',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('avisos')->insert([
            'titulo' => 'Horario de préstamos',
            'texto' => 'Los préstamos se atienden de 8:00 a 15:00 horas.',
            'fecha' => '2026-10-06',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('avisos')->insert([
            'titulo' => 'Mantenimiento de proyectores',
            'texto' => 'Un proyector estará fuera de servicio por mantenimiento.',
            'fecha' => '2026-10-08',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
