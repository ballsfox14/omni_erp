<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\Movimiento;
use App\Models\TipoMovimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
/**
 * Display a listing of the resource.
 */
public function index(Request $request)
{
    // Obtener bancos y tipos para filtros
    $bancos = Banco::where('activo', true)->get();
    $tiposMovimiento = TipoMovimiento::where('activo', true)->get();
    
    // Obtener días únicos que tienen movimientos (para PostgreSQL)
    // Usamos CAST para convertir fecha a date en PostgreSQL
    $diasQuery = Movimiento::select(DB::raw("fecha::date as dia"))
        ->groupBy(DB::raw("fecha::date"))
        ->orderBy('dia', 'desc');
    
    // Aplicar filtros a la consulta de días
    if ($request->filled('banco_id')) {
        $diasQuery->where('banco_id', $request->banco_id);
    }
    
    if ($request->filled('tipo_movimiento_id')) {
        $diasQuery->where('tipo_movimiento_id', $request->tipo_movimiento_id);
    }
    
    if ($request->filled('fecha_inicio')) {
        $diasQuery->where('fecha', '>=', $request->fecha_inicio);
    }
    
    if ($request->filled('fecha_fin')) {
        $diasQuery->where('fecha', '<=', $request->fecha_fin);
    }
    
    // Paginar los días (1 día por página)
    $diasPaginados = $diasQuery->paginate(1);
    
    // Asegurar que los filtros se mantengan en la paginación
    if ($request->filled('banco_id')) {
        $diasPaginados->appends(['banco_id' => $request->banco_id]);
    }
    if ($request->filled('tipo_movimiento_id')) {
        $diasPaginados->appends(['tipo_movimiento_id' => $request->tipo_movimiento_id]);
    }
    if ($request->filled('fecha_inicio')) {
        $diasPaginados->appends(['fecha_inicio' => $request->fecha_inicio]);
    }
    if ($request->filled('fecha_fin')) {
        $diasPaginados->appends(['fecha_fin' => $request->fecha_fin]);
    }
    
    // Si hay días, obtener los movimientos del día actual de la página
    $movimientosPorDia = collect();
    $totalesPorDia = [
        'total_debe' => 0,
        'total_haber' => 0,
        'saldo_inicial_dia' => 0,
        'saldo_final_dia' => 0
    ];
    
    if ($diasPaginados->count() > 0) {
        $diaActual = $diasPaginados[0]->dia;
        
        // Construir consulta para movimientos del día actual
        $queryMovimientos = Movimiento::with(['banco', 'tipoMovimiento'])
            ->whereDate('fecha', $diaActual)
            ->orderBy('created_at', 'asc') // Ordenar por hora de creación
            ->orderBy('id', 'asc');
        
        // Aplicar los mismos filtros a los movimientos
        if ($request->filled('banco_id')) {
            $queryMovimientos->where('banco_id', $request->banco_id);
        }
        
        if ($request->filled('tipo_movimiento_id')) {
            $queryMovimientos->where('tipo_movimiento_id', $request->tipo_movimiento_id);
        }
        
        $movimientosPorDia = $queryMovimientos->get();
        
        // Calcular totales del día
        $totalesPorDia['total_debe'] = $movimientosPorDia->sum('monto_debe');
        $totalesPorDia['total_haber'] = $movimientosPorDia->sum('monto_haber');
        
        // CALCULAR SALDO INICIAL DEL DÍA CORRECTAMENTE
        if ($request->filled('banco_id')) {
            $banco = Banco::find($request->banco_id);
            
            // Verificar si hay movimientos ANTES del día actual
            $hayMovimientosAnteriores = Movimiento::where('banco_id', $request->banco_id)
                ->whereDate('fecha', '<', $diaActual)
                ->exists();
            
            if (!$hayMovimientosAnteriores) {
                // Si NO hay movimientos anteriores a este día, usar el saldo inicial del banco
                $totalesPorDia['saldo_inicial_dia'] = $banco->saldo_inicial;
            } else {
                // Si HAY movimientos anteriores, obtener el último saldo posterior
                $ultimoMovimientoAnterior = Movimiento::where('banco_id', $request->banco_id)
                    ->whereDate('fecha', '<', $diaActual)
                    ->orderBy('fecha', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                $totalesPorDia['saldo_inicial_dia'] = $ultimoMovimientoAnterior->saldo_posterior;
            }
        } else {
            // Si NO hay filtro de banco, NO MOSTRAR saldos
            $totalesPorDia['saldo_inicial_dia'] = 0;
            $totalesPorDia['saldo_final_dia'] = 0;
        }
        
        // Calcular saldo final del día (solo si hay filtro de banco)
        if ($request->filled('banco_id')) {
            $totalesPorDia['saldo_final_dia'] = $totalesPorDia['saldo_inicial_dia'] 
                + $totalesPorDia['total_debe'] 
                - $totalesPorDia['total_haber'];
        }
    }
    
    // Calcular estadísticas generales (sin paginación, solo con filtros)
    $queryEstadisticas = Movimiento::query();
    
    if ($request->filled('banco_id')) {
        $queryEstadisticas->where('banco_id', $request->banco_id);
    }
    
    if ($request->filled('tipo_movimiento_id')) {
        $queryEstadisticas->where('tipo_movimiento_id', $request->tipo_movimiento_id);
    }
    
    if ($request->filled('fecha_inicio')) {
        $queryEstadisticas->where('fecha', '>=', $request->fecha_inicio);
    }
    
    if ($request->filled('fecha_fin')) {
        $queryEstadisticas->where('fecha', '<=', $request->fecha_fin);
    }
    
    $totalMovimientos = $queryEstadisticas->count();
    $totalIngresosGeneral = $queryEstadisticas->sum('monto_debe');
    $totalEgresosGeneral = $queryEstadisticas->sum('monto_haber');
    
    return view('movimientos.index', compact(
        'movimientosPorDia',
        'diasPaginados',
        'bancos',
        'tiposMovimiento',
        'totalesPorDia',
        'totalMovimientos',
        'totalIngresosGeneral',
        'totalEgresosGeneral'
    ));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bancos = Banco::where('activo', true)->get();
        $tiposMovimiento = TipoMovimiento::where('activo', true)->get();

        return view('movimientos.create', compact('bancos', 'tiposMovimiento'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'tipo_movimiento_id' => 'required|exists:tipos_movimientos,id',
            'fecha' => 'required|date',
            'concepto' => 'required|max:200',
            'referencia' => 'nullable|max:100',
            'monto_debe' => 'required_without:monto_haber|numeric|min:0',
            'monto_haber' => 'required_without:monto_debe|numeric|min:0',
            'descripcion' => 'nullable'
        ]);

        // Asegurarse de que al menos un monto sea mayor que 0
        if ($request->monto_debe == 0 && $request->monto_haber == 0) {
            return back()->withErrors(['monto_debe' => 'Debe ingresar un monto en debe o haber.'])->withInput();
        }

        // Crear el movimiento con la fecha actual para created_at
        $movimiento = Movimiento::create([
            'banco_id' => $request->banco_id,
            'tipo_movimiento_id' => $request->tipo_movimiento_id,
            'fecha' => $request->fecha,
            'concepto' => $request->concepto,
            'referencia' => $request->referencia,
            'monto_debe' => $request->monto_debe ?? 0,
            'monto_haber' => $request->monto_haber ?? 0,
            'descripcion' => $request->descripcion,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Actualizar el saldo actual del banco
        $banco = Banco::find($request->banco_id);
        $banco->actualizarSaldo();

        return redirect()->route('movimientos.index')->with('success', 'Movimiento creado exitosamente. Hora registrada: ' . now()->format('h:i A'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Movimiento $movimiento)
    {
        return view('movimientos.show', compact('movimiento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movimiento $movimiento)
    {
        $bancos = Banco::where('activo', true)->get();
        $tiposMovimiento = TipoMovimiento::where('activo', true)->get();

        return view('movimientos.edit', compact('movimiento', 'bancos', 'tiposMovimiento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movimiento $movimiento)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'tipo_movimiento_id' => 'required|exists:tipos_movimientos,id',
            'fecha' => 'required|date',
            'concepto' => 'required|max:200',
            'referencia' => 'nullable|max:100',
            'monto_debe' => 'required_without:monto_haber|numeric|min:0',
            'monto_haber' => 'required_without:monto_debe|numeric|min:0',
            'descripcion' => 'nullable'
        ]);

        // Guardar el banco anterior para actualizar su saldo después
        $bancoAnteriorId = $movimiento->banco_id;

        // Actualizar el movimiento
        $movimiento->update([
            'banco_id' => $request->banco_id,
            'tipo_movimiento_id' => $request->tipo_movimiento_id,
            'fecha' => $request->fecha,
            'concepto' => $request->concepto,
            'referencia' => $request->referencia,
            'monto_debe' => $request->monto_debe ?? 0,
            'monto_haber' => $request->monto_haber ?? 0,
            'descripcion' => $request->descripcion,
            'updated_at' => now()
        ]);

        // Recalcular todos los saldos posteriores para el banco afectado
        $this->recalcularSaldosBanco($movimiento->banco_id);

        // Si cambió de banco, también recalcular saldos del banco anterior
        if ($bancoAnteriorId != $movimiento->banco_id) {
            $this->recalcularSaldosBanco($bancoAnteriorId);
        }

        return redirect()->route('movimientos.index')->with('success', 'Movimiento actualizado exitosamente. Última modificación: ' . now()->format('h:i A'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movimiento $movimiento)
    {
        $bancoId = $movimiento->banco_id;
        $movimiento->delete();

        // Recalcular todos los saldos posteriores para el banco
        $this->recalcularSaldosBanco($bancoId);

        return redirect()->route('movimientos.index')->with('success', 'Movimiento eliminado exitosamente');
    }

    /**
     * Función para recalcular todos los saldos de un banco
     */
    private function recalcularSaldosBanco($bancoId)
    {
        // Obtener todos los movimientos del banco ordenados por fecha y hora de creación
        $movimientos = Movimiento::where('banco_id', $bancoId)
            ->orderBy('fecha', 'asc')
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Obtener el saldo inicial del banco
        $banco = Banco::find($bancoId);
        $saldoActual = $banco->saldo_inicial;

        // Recalcular cada movimiento
        foreach ($movimientos as $movimiento) {
            $movimiento->saldo_anterior = $saldoActual;
            $movimiento->saldo_posterior = $saldoActual
                + floatval($movimiento->monto_debe)
                - floatval($movimiento->monto_haber);
            $movimiento->saveQuietly(); // Guardar sin disparar eventos

            $saldoActual = $movimiento->saldo_posterior;
        }

        // Actualizar el saldo actual del banco
        $banco->actualizarSaldo();
    }
}