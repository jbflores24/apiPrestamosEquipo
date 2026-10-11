<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aviso extends Model
{
    protected $fillable = ['titulo', 'texto', 'fecha'];

    protected function casts(): array
    {
        return ['fecha' => 'date:Y-m-d'];
    }
}
