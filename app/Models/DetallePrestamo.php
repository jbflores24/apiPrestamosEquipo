<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

class DetallePrestamo extends Pivot
{
    protected $table = 'detalle_prestamos';

    public $incrementing = true;

    protected $fillable = ['prestamo_id', 'equipo_id', 'fecha_devolucion_real', 'observaciones'];

    protected $appends = ['dias_retraso'];

    protected $hidden = ['prestamo'];

    protected function casts(): array
    {
        return ['fecha_devolucion_real' => 'date:Y-m-d'];
    }

    public function prestamo(): BelongsTo
    {
        return $this->belongsTo(Prestamo::class);
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    public function getDiasRetrasoAttribute(): int
    {
        $esperada = $this->prestamo?->fecha_devolucion_esperada;

        if ($esperada === null) {
            return 0;
        }

        $fin = $this->fecha_devolucion_real ?? Carbon::today();

        return max(0, (int) $esperada->startOfDay()->diffInDays($fin->copy()->startOfDay(), false));
    }
}
