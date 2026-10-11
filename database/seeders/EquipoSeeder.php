<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('equipos')->insert([
            'codigo' => 'EQ-001',
            'nombre' => 'Laptop Dell Latitude',
            'tipo' => 'Laptop',
            'estado' => 'prestado',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('equipos')->insert([
            'codigo' => 'EQ-002',
            'nombre' => 'Proyector Epson X41',
            'tipo' => 'Proyector',
            'estado' => 'prestado',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('equipos')->insert([
            'codigo' => 'EQ-003',
            'nombre' => 'Cámara Canon T7',
            'tipo' => 'Cámara',
            'estado' => 'disponible',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('equipos')->insert([
            'codigo' => 'EQ-004',
            'nombre' => 'Tableta Samsung Tab A8',
            'tipo' => 'Tableta',
            'estado' => 'disponible',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('equipos')->insert([
            'codigo' => 'EQ-005',
            'nombre' => 'Proyector BenQ MS535',
            'tipo' => 'Proyector',
            'estado' => 'mantenimiento',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
