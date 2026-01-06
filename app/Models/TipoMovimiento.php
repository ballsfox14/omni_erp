<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMovimiento extends Model
{
    protected $table = 'tipos_movimientos';
    
    protected $fillable = [
        'nombre',
        'tipo',
        'descripcion',
        'activo'
    ];

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'tipo_movimiento_id');
    }
}