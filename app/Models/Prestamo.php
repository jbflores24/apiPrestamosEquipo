<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prestamo extends Model
{
    protected $fillable = ['alumno_id', 'fecha_salida', 'fecha_devolucion_esperada'];

    protected function casts(): array
    {
        return [
            'fecha_salida' => 'date:Y-m-d',
            'fecha_devolucion_esperada' => 'date:Y-m-d',
        ];
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function equipos(): BelongsToMany
    {
        return $this->belongsToMany(Equipo::class, 'detalle_prestamos')
            ->using(DetallePrestamo::class)
            ->withPivot('id', 'fecha_devolucion_real', 'observaciones')
            ->withTimestamps();
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetallePrestamo::class)->chaperone();
    }
}
