<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Equipo extends Model
{
    public const DISPONIBLE = 'disponible';
    public const PRESTADO = 'prestado';
    public const MANTENIMIENTO = 'mantenimiento';

    protected $fillable = ['codigo', 'nombre', 'tipo', 'estado'];

    public function prestamos(): BelongsToMany
    {
        return $this->belongsToMany(Prestamo::class, 'detalle_prestamos')
            ->using(DetallePrestamo::class)
            ->withPivot('id', 'fecha_devolucion_real', 'observaciones')
            ->withTimestamps();
    }
}
