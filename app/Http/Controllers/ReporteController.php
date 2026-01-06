<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\Movimiento;
use App\Models\TipoMovimiento;
use App\Models\EmpresaConfig; // <-- AÑADIR ESTA LÍNEA
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReporteController extends Controller
{
    /**
     * Mostrar formulario para reporte por banco
     */
    public function indexBanco()
    {
        $bancos = Banco::all();
        return view('reportes.banco', compact('bancos'));
    }

    /**
     * Generar reporte por banco (HTML o PDF)
     */
    public function generarReporteBanco(Request $request)
    {
        // Validar con manejo de errores
        $validator = Validator::make($request->all(), [
            'banco_id' => 'required|exists:bancos,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ], [
            'banco_id.required' => 'Debe seleccionar un banco',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
            'fecha_fin.required' => 'La fecha de fin es obligatoria',
            'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio',
        ]);

        if ($validator->fails()) {
            return redirect()->route('reportes.banco')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Por favor, verifica los errores en el formulario');
        }

        $validated = $validator->validated();
        $banco = Banco::findOrFail($validated['banco_id']);
        
        // Definir variables para la vista
        $fecha_inicio = $validated['fecha_inicio'];
        $fecha_fin = $validated['fecha_fin'];

        // Obtener movimientos del período
        $movimientos = Movimiento::where('banco_id', $banco->id)
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->orderBy('fecha', 'desc')
            ->with('tipoMovimiento')
            ->get();

        // Calcular saldo inicial
        $saldoInicial = $banco->saldo_inicial + 
            Movimiento::where('banco_id', $banco->id)
                ->where('fecha', '<', $fecha_inicio)
                ->selectRaw('COALESCE(SUM(monto_debe - monto_haber), 0) as neto')
                ->value('neto');

        // Calcular totales
        $totalIngresos = $movimientos->where('tipoMovimiento.tipo', 'ingreso')->sum('monto_debe');
        $totalEgresos = $movimientos->where('tipoMovimiento.tipo', 'egreso')->sum('monto_haber');
        $saldoFinal = $saldoInicial + $totalIngresos - $totalEgresos;

        // Si se solicita PDF
        if ($request->has('format') && $request->format === 'pdf') {
            // OBTENER CONFIGURACIÓN DE EMPRESA
            $empresaConfig = EmpresaConfig::getConfig();
            
            $data = [
                'banco' => $banco,
                'movimientos' => $movimientos,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'saldo_inicial' => $saldoInicial,
                'total_ingresos' => $totalIngresos,
                'total_egresos' => $totalEgresos,
                'saldo_final' => $saldoFinal,
                'empresaConfig' => $empresaConfig, // <-- PASAR LA CONFIGURACIÓN
                'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            ];

            try {
                $pdf = Pdf::loadView('reportes.pdf.banco', $data);
                $pdf->setPaper('A4', 'portrait');

                if ($request->has('preview') && $request->preview === 'true') {
                    return $pdf->stream();
                }

                return $pdf->download('reporte-banco-' . $banco->nombre . '-' . now()->format('Y-m-d') . '.pdf');
            } catch (\Exception $e) {
                return back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
            }
        }

        // Si es HTML, retornar vista con los datos
        return view('reportes.banco', compact(
            'banco', 
            'movimientos', 
            'saldoInicial', 
            'totalIngresos', 
            'totalEgresos', 
            'saldoFinal',
            'fecha_inicio',
            'fecha_fin'
        ))->with('bancos', Banco::all());
    }

    /**
     * Mostrar formulario para reporte consolidado
     */
    public function indexConsolidado()
    {
        return view('reportes.consolidado');
    }

    /**
     * Generar reporte consolidado (HTML o PDF)
     */
    public function generarReporteConsolidado(Request $request)
    {
        // Validar con manejo de errores y valores por defecto
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ], [
            'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio',
        ]);

        if ($validator->fails()) {
            if ($request->has('format') && $request->format === 'pdf') {
                return redirect()->back()
                    ->with('error', 'Error en fechas: ' . $validator->errors()->first());
            }
            
            return redirect()->route('reportes.consolidado')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Por favor, verifica los errores en el formulario');
        }

        $validated = $validator->validated();
        
        // Establecer valores por defecto si no hay fechas
        $fechaInicio = $validated['fecha_inicio'] ?? now()->startOfMonth()->format('Y-m-d');
        $fechaFin = $validated['fecha_fin'] ?? now()->endOfMonth()->format('Y-m-d');

        $bancos = Banco::all();
        $reportePorBanco = [];
        $totalIngresosConsolidado = 0;
        $totalEgresosConsolidado = 0;
        $totalMovimientosConsolidado = 0;
        $saldoInicialConsolidado = 0;
        $saldoFinalConsolidado = 0;

        foreach ($bancos as $banco) {
            // Movimientos en el período
            $movimientos = Movimiento::where('banco_id', $banco->id)
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->with('tipoMovimiento')
                ->get();

            // Saldo inicial
            $saldoInicial = $banco->saldo_inicial + 
                Movimiento::where('banco_id', $banco->id)
                    ->where('fecha', '<', $fechaInicio)
                    ->selectRaw('COALESCE(SUM(monto_debe - monto_haber), 0) as neto')
                    ->value('neto');

            $totalIngresos = $movimientos->where('tipoMovimiento.tipo', 'ingreso')->sum('monto_debe');
            $totalEgresos = $movimientos->where('tipoMovimiento.tipo', 'egreso')->sum('monto_haber');
            $saldoFinal = $saldoInicial + $totalIngresos - $totalEgresos;

            $reportePorBanco[] = [
                'banco' => $banco,
                'saldo_inicial' => $saldoInicial,
                'total_ingresos' => $totalIngresos,
                'total_egresos' => $totalEgresos,
                'saldo_final' => $saldoFinal,
                'movimientos_count' => $movimientos->count(),
            ];

            // Consolidar
            $saldoInicialConsolidado += $saldoInicial;
            $totalIngresosConsolidado += $totalIngresos;
            $totalEgresosConsolidado += $totalEgresos;
            $saldoFinalConsolidado += $saldoFinal;
            $totalMovimientosConsolidado += $movimientos->count();
        }

        $saldoNetoConsolidado = $totalIngresosConsolidado - $totalEgresosConsolidado;

        // Desglose por tipo de movimiento
        $tiposMovimiento = TipoMovimiento::all();
        $movimientosPorTipo = [];

        foreach ($tiposMovimiento as $tipo) {
            if ($tipo->tipo == 'ingreso') {
                $sum = Movimiento::where('tipo_movimiento_id', $tipo->id)
                    ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                    ->sum('monto_debe');
            } else {
                $sum = Movimiento::where('tipo_movimiento_id', $tipo->id)
                    ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                    ->sum('monto_haber');
            }
            
            if ($sum != 0) {
                $movimientosPorTipo[$tipo->id] = $sum;
            }
        }

        // Si se solicita PDF
        if ($request->has('format') && $request->format === 'pdf') {
            // OBTENER CONFIGURACIÓN DE EMPRESA
            $empresaConfig = EmpresaConfig::getConfig();
            
            $data = [
                'consolidado' => $reportePorBanco,
                'totales' => [
                    'saldo_inicial' => $saldoInicialConsolidado,
                    'ingresos' => $totalIngresosConsolidado,
                    'egresos' => $totalEgresosConsolidado,
                    'saldo_final' => $saldoFinalConsolidado,
                    'movimientos_count' => $totalMovimientosConsolidado,
                ],
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'empresaConfig' => $empresaConfig, // <-- PASAR LA CONFIGURACIÓN
                'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            ];

            try {
                $pdf = Pdf::loadView('reportes.pdf.consolidado', $data);
                $pdf->setPaper('A4', 'landscape');

                if ($request->has('preview') && $request->preview === 'true') {
                    return $pdf->stream();
                }

                return $pdf->download('reporte-consolidado-' . now()->format('Y-m-d') . '.pdf');
            } catch (\Exception $e) {
                return back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
            }
        }

        // Si es HTML
        return view('reportes.consolidado', compact(
            'reportePorBanco',
            'totalIngresosConsolidado',
            'totalEgresosConsolidado',
            'saldoNetoConsolidado',
            'totalMovimientosConsolidado',
            'saldoInicialConsolidado',
            'saldoFinalConsolidado',
            'tiposMovimiento',
            'movimientosPorTipo'
        ))->with('fecha_inicio', $fechaInicio)
          ->with('fecha_fin', $fechaFin);
    }
}