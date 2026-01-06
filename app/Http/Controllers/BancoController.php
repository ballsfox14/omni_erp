<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\Movimiento;
use Illuminate\Http\Request;

class BancoController extends Controller
{
    public function index()
    {
        $bancos = Banco::paginate(10);
        return view('bancos.index', compact('bancos'));
    }

    public function create()
    {
        return view('bancos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'numero_cuenta' => 'required|unique:bancos|max:50',
            'nombre_propietario' => 'required|max:200',
            'saldo_inicial' => 'required|numeric|min:0',
            'descripcion' => 'nullable'
        ]);

        // **SOLUCIÓN: NO CREAR MOVIMIENTO DE SALDO INICIAL**
        // Solo guardar el saldo inicial como referencia
        Banco::create([
            'nombre' => $request->nombre,
            'numero_cuenta' => $request->numero_cuenta,
            'nombre_propietario' => $request->nombre_propietario,
            'saldo_inicial' => $request->saldo_inicial,
            'saldo_actual' => $request->saldo_inicial, // Inicia con este saldo
            'descripcion' => $request->descripcion,
            'activo' => $request->has('activo')
        ]);

        return redirect()->route('bancos.index')->with('success', 'Banco creado exitosamente');
    }

    // ... resto de los métodos se mantienen igual
    public function show(Banco $banco)
    {
        $totalIngresos = Movimiento::where('banco_id', $banco->id)
            ->whereHas('tipoMovimiento', function($query) {
                $query->where('tipo', 'ingreso');
            })
            ->sum('monto_debe');
        
        $totalEgresos = Movimiento::where('banco_id', $banco->id)
            ->whereHas('tipoMovimiento', function($query) {
                $query->where('tipo', 'egreso');
            })
            ->sum('monto_haber');
        
        $totalMovimientos = Movimiento::where('banco_id', $banco->id)->count();
        $movimientos = Movimiento::where('banco_id', $banco->id)
            ->with('tipoMovimiento')
            ->latest()
            ->take(10)
            ->get();
        
        return view('bancos.show', compact(
            'banco', 
            'totalIngresos', 
            'totalEgresos', 
            'totalMovimientos',
            'movimientos'
        ));
    }

    public function edit(Banco $banco)
    {
        return view('bancos.edit', compact('banco'));
    }

    public function update(Request $request, Banco $banco)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'numero_cuenta' => 'required|unique:bancos,numero_cuenta,' . $banco->id . '|max:50',
            'nombre_propietario' => 'required|max:200',
            'descripcion' => 'nullable'
        ]);

        $datosActualizacion = [
            'nombre' => $request->nombre,
            'numero_cuenta' => $request->numero_cuenta,
            'nombre_propietario' => $request->nombre_propietario,
            'descripcion' => $request->descripcion,
            'activo' => $request->has('activo')
        ];

        // Solo permitir actualizar saldo_inicial si no hay movimientos
        if ($banco->isSaldoInicialModificable()) {
            $request->validate([
                'saldo_inicial' => 'required|numeric|min:0'
            ]);
            
            $datosActualizacion['saldo_inicial'] = $request->saldo_inicial;
            $datosActualizacion['saldo_actual'] = $request->saldo_inicial;
        }

        $banco->update($datosActualizacion);

        return redirect()->route('bancos.index')->with('success', 'Banco actualizado');
    }

    public function destroy(Banco $banco)
    {
        $banco->delete();
        return redirect()->route('bancos.index')->with('success', 'Banco eliminado');
    }
}