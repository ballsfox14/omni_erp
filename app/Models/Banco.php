<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $fillable = [
        'nombre',
        'numero_cuenta',
        'nombre_propietario', // Nuevo campo
        'saldo_inicial',
        'saldo_actual',
        'descripcion',
        'activo'
    ];

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

public function actualizarSaldo()
{
    $ultimoMovimiento = $this->movimientos()
        ->latest('fecha')
        ->latest('id')
        ->first();

    // Si hay movimientos, usar el saldo_posterior del último movimiento
    // Si no hay movimientos, usar el saldo_inicial (que ya es el saldo real)
    $this->saldo_actual = $ultimoMovimiento 
        ? $ultimoMovimiento->saldo_posterior 
        : $this->saldo_inicial;
    
    $this->saveQuietly();
}

    // Validación personalizada para saldo inicial inmodificable
    public function isSaldoInicialModificable()
    {
        // El saldo inicial no se puede modificar si ya existen movimientos
        return $this->movimientos()->count() === 0;
    }
}