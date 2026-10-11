<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

class DetallePrestamo extends Pivot
{
    protected $table = 'detalle_prestamos';

    public $incrementing = true;

    protected $fillable = [
        'prestamo_id',
        'equipo_id',
        'fecha_devolucion_real',
        'observaciones'
    ];

    public function prestamo(){
        return $this->belongsTo(Prestamo::class);
    }

    public function equipo(){
        return $this->belongsTo(Equipo::class);
    }

    public function getDiasRetrasoAttribute(){
        $esperada = Carbon::parse($this->prestamo->fecha_devolucion_esperada)->startOfDay();
        $fin = $this->fecha_devolucion_real ?? Carbon::today();
        return max(0, (int) $esperada->diffInDays(Carbon::parse($fin)->startOfDay(), false));
    }
}
