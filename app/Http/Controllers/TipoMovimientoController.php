<?php

namespace App\Http\Controllers;

use App\Models\TipoMovimiento;
use App\Models\Banco;
use Illuminate\Http\Request;

class TipoMovimientoController extends Controller
{
    public function index()
    {
        $tipos = TipoMovimiento::all();
        $bancos = Banco::all();
        
        return view('tipos-movimiento.index', compact('tipos', 'bancos'));
    }

    public function create()
    {
        return view('tipos-movimiento.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100|unique:tipos_movimientos,nombre',
            'tipo' => 'required|in:ingreso,egreso',
            'descripcion' => 'nullable|max:255'
        ], [
            'nombre.unique' => 'El nombre del tipo de movimiento ya existe. Por favor, elija un nombre diferente.',
            'nombre.required' => 'El nombre del tipo de movimiento es obligatorio.',
            'tipo.required' => 'El tipo (ingreso/egreso) es obligatorio.',
            'tipo.in' => 'El tipo debe ser "ingreso" o "egreso".',
            'descripcion.max' => 'La descripción no puede exceder los 255 caracteres.'
        ]);

        TipoMovimiento::create([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'activo' => true
        ]);

        return redirect()->route('tipos-movimiento.index')
            ->with('success', 'Tipo de movimiento creado exitosamente');
    }

    public function show($id)
    {
        $tipoMovimiento = TipoMovimiento::findOrFail($id);
        return view('tipos-movimiento.show', compact('tipoMovimiento'));
    }

    public function edit($id)
    {
        $tipoMovimiento = TipoMovimiento::findOrFail($id);
        return view('tipos-movimiento.edit', compact('tipoMovimiento'));
    }

    public function update(Request $request, $id)
    {
        $tipoMovimiento = TipoMovimiento::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|max:100|unique:tipos_movimientos,nombre,' . $id,
            'tipo' => 'required|in:ingreso,egreso',
            'descripcion' => 'nullable|max:255',
            'activo' => 'boolean'
        ], [
            'nombre.unique' => 'El nombre del tipo de movimiento ya existe. Por favor, elija un nombre diferente.',
            'nombre.required' => 'El nombre del tipo de movimiento es obligatorio.',
            'tipo.required' => 'El tipo (ingreso/egreso) es obligatorio.',
            'tipo.in' => 'El tipo debe ser "ingreso" o "egreso".',
            'descripcion.max' => 'La descripción no puede exceder los 255 caracteres.'
        ]);

        $tipoMovimiento->update([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'activo' => $request->activo ?? true
        ]);

        return redirect()->route('tipos-movimiento.index')
            ->with('success', 'Tipo de movimiento actualizado exitosamente');
    }

    public function destroy($id)
    {
        $tipoMovimiento = TipoMovimiento::findOrFail($id);
        
        if ($tipoMovimiento->movimientos()->count() > 0) {
            return redirect()->route('tipos-movimiento.index')
                ->with('error', 'No se puede eliminar porque tiene movimientos asociados');
        }

        $tipoMovimiento->delete();
        return redirect()->route('tipos-movimiento.index')
            ->with('success', 'Tipo de movimiento eliminado exitosamente');
    }
}