<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call (AvisoSeeder::class);
        $this->call (AlumnoSeeder::class);
        $this->call (EquipoSeeder::class);
        $this->call (PrestamoSeeder::class);
        $this->call (DetallePrestamoSeeder::class);
    }
}
