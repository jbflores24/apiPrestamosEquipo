<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumno extends Model
{
    protected $fillable = ['matricula', 'nombre', 'carrera'];

    public function prestamos(): HasMany
    {
        return $this->hasMany(Prestamo::class);
    }
}
