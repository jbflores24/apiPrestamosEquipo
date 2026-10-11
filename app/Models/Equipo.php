<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    const DISPONIBLE = 'disponible';
    const PRESTADO = 'prestado';
    const MANTENIMIENTO = 'mantenimiento';

    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',
        'estado'
    ];

    public function prestamos(){
        return $this->belongsToMany(Prestamo::class, 'detalle_prestamos')
            ->using(DetallePrestamo::class)
            ->withPivot('id', 'fecha_devolucion_real', 'observaciones')
            ->withTimestamps();
    }
}
