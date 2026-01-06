<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreMensual extends Model
{
    use HasFactory;

    // DEFINIR EL NOMBRE DE LA TABLA EXPLÍCITAMENTE
    protected $table = 'cierres_mensuales';

    protected $fillable = [
        'banco_id',
        'ultimo_movimiento_id',
        'fecha_cierre',
        'saldo_final',
        'total_ingresos',
        'total_egresos',
        'cantidad_movimientos',
        'observaciones',
        'cerrado',
        'user_id'
    ];

    protected $casts = [
        'fecha_cierre' => 'date',
        'saldo_final' => 'decimal:2',
        'total_ingresos' => 'decimal:2',
        'total_egresos' => 'decimal:2',
        'cerrado' => 'boolean'
    ];

    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ultimoMovimiento()
    {
        return $this->belongsTo(Movimiento::class, 'ultimo_movimiento_id');
    }

    // Obtener el último cierre de un banco
    public static function ultimoCierreBanco($banco_id)
    {
        return self::where('banco_id', $banco_id)
            ->where('cerrado', true)
            ->orderBy('fecha_cierre', 'desc')
            ->first();
    }

    // Verificar si un mes está cerrado para un banco
    public static function mesCerrado($banco_id, $fecha)
    {
        $fecha = \Carbon\Carbon::parse($fecha);
        $inicioMes = $fecha->copy()->startOfMonth();
        $finMes = $fecha->copy()->endOfMonth();

        return self::where('banco_id', $banco_id)
            ->where('fecha_cierre', '>=', $inicioMes)
            ->where('fecha_cierre', '<=', $finMes)
            ->where('cerrado', true)
            ->exists();
    }

    // En el modelo CierreMensual.php - Método completo CORREGIDO
    public static function calcularInformacionCierre($banco_id, $mes)
    {
        if (!$banco_id || !$mes) {
            throw new \Exception('Parámetros inválidos: banco_id y mes son requeridos');
        }

        try {
            $fecha = \Carbon\Carbon::createFromFormat('Y-m', $mes);
        } catch (\Exception $e) {
            throw new \Exception('Formato de mes inválido. Use YYYY-MM');
        }

        $fechaInicio = $fecha->copy()->startOfMonth();
        $fechaFin = $fecha->copy()->endOfMonth();

        $banco = Banco::find($banco_id);
        
        if (!$banco) {
            throw new \Exception('Banco no encontrado');
        }
        
        $movimientos = Movimiento::where('banco_id', $banco_id)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get();

        $ultimoMovimiento = $movimientos->sortByDesc('fecha')
            ->sortByDesc('id')
            ->first();

        $totalIngresos = $movimientos->sum('monto_debe');
        $totalEgresos = $movimientos->sum('monto_haber');
        $cantidadMovimientos = $movimientos->count();

        if ($ultimoMovimiento) {
            $saldoFinal = $ultimoMovimiento->saldo_posterior;
            $ultimoMovimientoFecha = $ultimoMovimiento->fecha->format('d/m/Y');
            $ultimoMovimientoId = $ultimoMovimiento->id;
        } else {
            $movimientoAnterior = Movimiento::where('banco_id', $banco_id)
                ->where('fecha', '<', $fechaInicio)
                ->orderBy('fecha', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            
            $saldoFinal = $movimientoAnterior ? $movimientoAnterior->saldo_posterior : $banco->saldo_inicial;
            $ultimoMovimientoFecha = 'No hay movimientos en el mes';
            $ultimoMovimientoId = null;
        }

        return [
            'saldo_final' => $saldoFinal,
            'total_ingresos' => $totalIngresos,
            'total_egresos' => $totalEgresos,
            'cantidad_movimientos' => $cantidadMovimientos,
            'ultimo_movimiento_id' => $ultimoMovimientoId,
            'ultimo_movimiento_fecha' => $ultimoMovimientoFecha,
            'banco_nombre' => $banco->nombre,
            'mes_formateado' => $fecha->translatedFormat('F Y')
        ];
    }

    // Nuevo método para cierre consolidado
    public static function generarConsolidado($mes)
    {
        $fecha = \Carbon\Carbon::parse($mes);
        $fechaInicio = $fecha->copy()->startOfMonth();
        $fechaFin = $fecha->copy()->endOfMonth();

        $bancos = Banco::where('activo', true)->get();
        $consolidado = [];
        $totales = [
            'total_ingresos' => 0,
            'total_egresos' => 0,
            'saldo_final' => 0,
            'total_movimientos' => 0
        ];

        foreach ($bancos as $banco) {
            $movimientos = Movimiento::where('banco_id', $banco->id)
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->get();

            $ultimoMovimiento = $movimientos->sortByDesc('fecha')
                ->sortByDesc('id')
                ->first();

            $saldoFinal = $ultimoMovimiento ? $ultimoMovimiento->saldo_posterior : $banco->saldo_actual;
            $totalIngresos = $movimientos->sum('monto_debe');
            $totalEgresos = $movimientos->sum('monto_haber');

            $consolidado[] = [
                'banco' => $banco,
                'saldo_final' => $saldoFinal,
                'total_ingresos' => $totalIngresos,
                'total_egresos' => $totalEgresos,
                'cantidad_movimientos' => $movimientos->count(),
                'ultimo_movimiento' => $ultimoMovimiento
            ];

            $totales['total_ingresos'] += $totalIngresos;
            $totales['total_egresos'] += $totalEgresos;
            $totales['saldo_final'] += $saldoFinal;
            $totales['total_movimientos'] += $movimientos->count();
        }

        return [
            'consolidado' => $consolidado,
            'totales' => $totales,
            'mes' => $fecha->translatedFormat('F Y'),
            'fecha_inicio' => $fechaInicio->format('Y-m-d'),
            'fecha_fin' => $fechaFin->format('Y-m-d')
        ];
    }
}