<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('alumnos')->insert([
            'matricula' => '20240001',
            'nombre' => 'Ana Martínez Peña',
            'carrera' => 'Ingeniería en Sistemas',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('alumnos')->insert([
            'matricula' => '20240002',
            'nombre' => 'Luis Hernández Núñez',
            'carrera' => 'Ingeniería Industrial',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('alumnos')->insert([
            'matricula' => '20240003',
            'nombre' => 'Carla Ñáñez Ruiz',
            'carrera' => 'Arquitectura',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
