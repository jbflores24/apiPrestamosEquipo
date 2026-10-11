<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $fillable = [
        'matricula',
        'nombre',
        'carrera'
    ];

    public function prestamos(){
        return $this->hasMany(Prestamo::class);
    }
}
