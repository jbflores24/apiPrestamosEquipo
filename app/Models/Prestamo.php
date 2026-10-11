<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $fillable = [
        'alumno_id',
        'fecha_salida',
        'fecha_devolucion_esperada'
    ];

    public function alumno(){
        return $this->belongsTo(Alumno::class);
    }

    public function equipos(){
        return $this->belongsToMany(Equipo::class, 'detalle_prestamos')
            ->using(DetallePrestamo::class)
            ->withPivot('id', 'fecha_devolucion_real', 'observaciones')
            ->withTimestamps();
    }

    public function detalles(){
        return $this->hasMany(DetallePrestamo::class)->chaperone();
    }
}
