<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Movimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'banco_id',
        'fecha',
        'tipo_movimiento_id',
        'concepto',
        'monto_debe',
        'monto_haber',
        'saldo_anterior',
        'saldo_posterior',
        'referencia',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'monto_debe' => 'decimal:2',
        'monto_haber' => 'decimal:2',
        'saldo_anterior' => 'decimal:2',
        'saldo_posterior' => 'decimal:2'
    ];

    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }

    public function tipoMovimiento()
    {
        return $this->belongsTo(TipoMovimiento::class);
    }

    protected static function booted()
    {
        static::updating(function ($movimiento) {
            $fechaOriginal = Carbon::parse($movimiento->getOriginal('fecha'));
            
            $mesCerrado = \App\Models\CierreMensual::where('banco_id', $movimiento->banco_id)
                ->whereYear('fecha_cierre', $fechaOriginal->year)
                ->whereMonth('fecha_cierre', $fechaOriginal->month)
                ->where('cerrado', true)
                ->exists();

            if ($mesCerrado) {
                throw new \Exception("No se puede modificar un movimiento de un mes ya cerrado.");
            }
        });

        static::deleting(function ($movimiento) {
            $fecha = Carbon::parse($movimiento->fecha);
            
            $mesCerrado = \App\Models\CierreMensual::where('banco_id', $movimiento->banco_id)
                ->whereYear('fecha_cierre', $fecha->year)
                ->whereMonth('fecha_cierre', $fecha->month)
                ->where('cerrado', true)
                ->exists();

            if ($mesCerrado) {
                throw new \Exception("No se puede eliminar un movimiento de un mes ya cerrado.");
            }
        });

        static::creating(function ($movimiento) {
            // Obtener el último movimiento ANTES de este nuevo movimiento (en orden cronológico)
            $ultimoMovimientoAnterior = self::where('banco_id', $movimiento->banco_id)
                ->where(function($query) use ($movimiento) {
                    $query->where('fecha', '<', $movimiento->fecha)
                          ->orWhere(function($q) use ($movimiento) {
                              $q->where('fecha', $movimiento->fecha)
                                ->where('id', '<', $movimiento->id ?? PHP_INT_MAX);
                          });
                })
                ->orderBy('fecha', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            $movimiento->saldo_anterior = $ultimoMovimientoAnterior
                ? $ultimoMovimientoAnterior->saldo_posterior
                : ($movimiento->banco->saldo_inicial ?? 0);

            $movimiento->saldo_posterior = $movimiento->saldo_anterior
                + floatval($movimiento->monto_debe)
                - floatval($movimiento->monto_haber);

            // Validar mes anterior cerrado
            $fechaMovimiento = Carbon::parse($movimiento->fecha);
            $mesAnterior = $fechaMovimiento->copy()->subMonth();
            
            $cierreMesAnterior = \App\Models\CierreMensual::where('banco_id', $movimiento->banco_id)
                ->whereYear('fecha_cierre', $mesAnterior->year)
                ->whereMonth('fecha_cierre', $mesAnterior->month)
                ->where('cerrado', true)
                ->exists();

            if (!$cierreMesAnterior) {
                \Log::info("Movimiento creado en el banco ID {$movimiento->banco_id} sin cierre del mes anterior ({$mesAnterior->format('Y-m')})");
            }
        });

        static::created(function ($movimiento) {
            // Recalcular todos los movimientos POSTERIORES a este, INCLUYENDO los del mismo día con ID mayor
            $movimientosPosteriores = self::where('banco_id', $movimiento->banco_id)
                ->where(function($query) use ($movimiento) {
                    $query->where('fecha', '>', $movimiento->fecha)
                          ->orWhere(function($q) use ($movimiento) {
                              $q->where('fecha', $movimiento->fecha)
                                ->where('id', '>', $movimiento->id);
                          });
                })
                ->orderBy('fecha', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $saldoActual = $movimiento->saldo_posterior;

            foreach ($movimientosPosteriores as $movimientoPosterior) {
                $saldoActual = self::recalcularSaldoMovimiento($movimientoPosterior, $saldoActual);
            }

            $movimiento->banco->actualizarSaldo();
        });

        static::updated(function ($movimiento) {
            // Obtener el saldo anterior correcto (el movimiento inmediatamente anterior)
            $movimientoAnterior = self::where('banco_id', $movimiento->banco_id)
                ->where(function($query) use ($movimiento) {
                    $query->where('fecha', '<', $movimiento->fecha)
                          ->orWhere(function($q) use ($movimiento) {
                              $q->where('fecha', $movimiento->fecha)
                                ->where('id', '<', $movimiento->id);
                          });
                })
                ->orderBy('fecha', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            $saldoAnterior = $movimientoAnterior
                ? $movimientoAnterior->saldo_posterior
                : $movimiento->banco->saldo_inicial;

            // Recalcular este movimiento
            $saldoActual = self::recalcularSaldoMovimiento($movimiento, $saldoAnterior);

            // Recalcular todos los movimientos posteriores
            $movimientosPosteriores = self::where('banco_id', $movimiento->banco_id)
                ->where(function($query) use ($movimiento) {
                    $query->where('fecha', '>', $movimiento->fecha)
                          ->orWhere(function($q) use ($movimiento) {
                              $q->where('fecha', $movimiento->fecha)
                                ->where('id', '>', $movimiento->id);
                          });
                })
                ->orderBy('fecha', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            foreach ($movimientosPosteriores as $movimientoPosterior) {
                $saldoActual = self::recalcularSaldoMovimiento($movimientoPosterior, $saldoActual);
            }

            $movimiento->banco->actualizarSaldo();
        });

        static::deleted(function ($movimiento) {
            // Obtener el saldo anterior al movimiento eliminado
            $movimientoAnterior = self::where('banco_id', $movimiento->banco_id)
                ->where(function($query) use ($movimiento) {
                    $query->where('fecha', '<', $movimiento->fecha)
                          ->orWhere(function($q) use ($movimiento) {
                              $q->where('fecha', $movimiento->fecha)
                                ->where('id', '<', $movimiento->id);
                          });
                })
                ->orderBy('fecha', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            $saldoActual = $movimientoAnterior
                ? $movimientoAnterior->saldo_posterior
                : $movimiento->banco->saldo_inicial;

            // Recalcular todos los movimientos posteriores
            $movimientosPosteriores = self::where('banco_id', $movimiento->banco_id)
                ->where(function($query) use ($movimiento) {
                    $query->where('fecha', '>', $movimiento->fecha)
                          ->orWhere(function($q) use ($movimiento) {
                              $q->where('fecha', $movimiento->fecha)
                                ->where('id', '>', $movimiento->id);
                          });
                })
                ->orderBy('fecha', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            foreach ($movimientosPosteriores as $movimientoPosterior) {
                $saldoActual = self::recalcularSaldoMovimiento($movimientoPosterior, $saldoActual);
            }

            $movimiento->banco->actualizarSaldo();
        });
    }

    /**
     * Método auxiliar para recalcular el saldo de un movimiento específico
     */
    private static function recalcularSaldoMovimiento($movimiento, $saldoAnterior)
    {
        $movimiento->saldo_anterior = $saldoAnterior;
        $movimiento->saldo_posterior = $saldoAnterior 
            + floatval($movimiento->monto_debe) 
            - floatval($movimiento->monto_haber);
        
        $movimiento->saveQuietly();
        
        return $movimiento->saldo_posterior;
    }

    public function getMesCerradoAttribute()
    {
        return \App\Models\CierreMensual::where('banco_id', $this->banco_id)
            ->whereYear('fecha_cierre', $this->fecha->year)
            ->whereMonth('fecha_cierre', $this->fecha->month)
            ->where('cerrado', true)
            ->exists();
    }

    public function cierreMensual()
    {
        return $this->hasOne(\App\Models\CierreMensual::class, 'banco_id', 'banco_id')
            ->whereYear('fecha_cierre', $this->fecha->year)
            ->whereMonth('fecha_cierre', $this->fecha->month)
            ->where('cerrado', true);
    }
}