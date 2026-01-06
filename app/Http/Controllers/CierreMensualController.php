<?php

namespace App\Http\Controllers;

use App\Models\CierreMensual;
use App\Models\Banco;
use App\Models\Movimiento;
use App\Models\EmpresaConfig; // <-- AÑADIR ESTA LÍNEA
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CierreMensualController extends Controller
{
    public function index()
    {
        $cierres = CierreMensual::with(['banco', 'usuario'])
            ->orderBy('fecha_cierre', 'desc')
            ->orderBy('banco_id')
            ->paginate(20);
        
        return view('cierres-mensuales.index', compact('cierres'));
    }

    public function create()
    {
        $bancos = Banco::where('activo', true)->get();
        return view('cierres-mensuales.create', compact('bancos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'mes' => 'required|date_format:Y-m',
            'observaciones' => 'nullable|string'
        ]);

        $fecha = Carbon::createFromFormat('Y-m', $request->mes);
        $fechaInicio = $fecha->copy()->startOfMonth();
        $fechaFin = $fecha->copy()->endOfMonth();

        $cierreExistente = CierreMensual::where('banco_id', $request->banco_id)
            ->whereYear('fecha_cierre', $fecha->year)
            ->whereMonth('fecha_cierre', $fecha->month)
            ->first();

        if ($cierreExistente) {
            return back()->withErrors(['mes' => 'Ya existe un cierre para este banco y mes.'])->withInput();
        }

        $infoCierre = CierreMensual::calcularInformacionCierre($request->banco_id, $request->mes);

        CierreMensual::create([
            'banco_id' => $request->banco_id,
            'ultimo_movimiento_id' => $infoCierre['ultimo_movimiento_id'],
            'fecha_cierre' => $fechaFin,
            'saldo_final' => $infoCierre['saldo_final'],
            'total_ingresos' => $infoCierre['total_ingresos'],
            'total_egresos' => $infoCierre['total_egresos'],
            'cantidad_movimientos' => $infoCierre['cantidad_movimientos'],
            'observaciones' => $request->observaciones,
            'cerrado' => true,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('cierres-mensuales.index')
            ->with('success', 'Cierre mensual registrado exitosamente.');
    }

    public function show($id)
    {
        $cierreMensual = CierreMensual::find($id);
        
        if (!$cierreMensual) {
            abort(404, 'Cierre mensual no encontrado');
        }

        $movimientos = Movimiento::where('banco_id', $cierreMensual->banco_id)
            ->whereYear('fecha', $cierreMensual->fecha_cierre->year)
            ->whereMonth('fecha', $cierreMensual->fecha_cierre->month)
            ->with('tipoMovimiento')
            ->orderBy('fecha')
            ->orderBy('id')
            ->get();

        return view('cierres-mensuales.show', compact('cierreMensual', 'movimientos'));
    }

    public function destroy(CierreMensual $cierreMensual)
    {
        $cierreMensual->delete();
        
        return redirect()->route('cierres-mensuales.index')
            ->with('success', 'Cierre mensual eliminado exitosamente.');
    }

    public function verificarCierre(Request $request)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'fecha' => 'required|date'
        ]);

        $fecha = Carbon::parse($request->fecha);
        $mesAnterior = $fecha->copy()->subMonth();
        $banco = Banco::find($request->banco_id);

        $cierreMesAnterior = CierreMensual::where('banco_id', $request->banco_id)
            ->whereYear('fecha_cierre', $mesAnterior->year)
            ->whereMonth('fecha_cierre', $mesAnterior->month)
            ->where('cerrado', true)
            ->exists();

        return response()->json([
            'mes_anterior_cerrado' => $cierreMesAnterior,
            'mes_anterior' => $mesAnterior->format('F Y'),
            'banco' => $banco->nombre
        ]);
    }

    public function crearAperturaMes(Request $request)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'fecha' => 'required|date'
        ]);

        $fecha = Carbon::parse($request->fecha);
        $inicioMes = $fecha->copy()->startOfMonth();
        $mesAnterior = $fecha->copy()->subMonth();

        $cierreAnterior = CierreMensual::where('banco_id', $request->banco_id)
            ->whereYear('fecha_cierre', $mesAnterior->year)
            ->whereMonth('fecha_cierre', $mesAnterior->month)
            ->where('cerrado', true)
            ->first();

        if (!$cierreAnterior) {
            return response()->json([
                'success' => false,
                'message' => 'No existe un cierre para el mes anterior.'
            ], 400);
        }

        $movimientoExistente = Movimiento::where('banco_id', $request->banco_id)
            ->whereDate('fecha', $inicioMes)
            ->where('concepto', 'like', '%apertura%')
            ->first();

        if ($movimientoExistente) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe un movimiento de apertura para este mes.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $movimiento = Movimiento::create([
                'banco_id' => $request->banco_id,
                'fecha' => $inicioMes,
                'tipo_movimiento_id' => 1,
                'concepto' => 'Apertura de caja mes ' . $fecha->format('F Y'),
                'monto_debe' => 0,
                'monto_haber' => 0,
                'saldo_anterior' => 0,
                'saldo_posterior' => $cierreAnterior->saldo_final,
                'referencia' => 'APERTURA-' . $fecha->format('Ym'),
                'observaciones' => 'Apertura de mes con saldo del cierre anterior'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Movimiento de apertura creado exitosamente.',
                'movimiento' => $movimiento
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el movimiento: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cierreDesdeMovimientos()
    {
        $bancos = Banco::where('activo', true)->get();
        $ultimoMes = Carbon::now()->subMonth()->format('Y-m');
        
        return view('cierres-mensuales.desde-movimientos', compact('bancos', 'ultimoMes'));
    }

    public function realizarCierre(Request $request)
    {
        $request->validate([
            'tipo_cierre' => 'required|in:individual,consolidado',
            'mes' => 'required|date_format:Y-m',
            'banco_id' => 'required_if:tipo_cierre,individual|exists:bancos,id',
            'observaciones' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $mes = $request->mes;
            $fechaCierre = Carbon::parse($mes)->endOfMonth();
            $user_id = auth()->id();

            if ($request->tipo_cierre == 'individual') {
                $banco_id = $request->banco_id;
                
                $existeCierre = CierreMensual::where('banco_id', $banco_id)
                    ->whereYear('fecha_cierre', $fechaCierre->year)
                    ->whereMonth('fecha_cierre', $fechaCierre->month)
                    ->exists();

                if ($existeCierre) {
                    return redirect()->back()
                        ->withErrors(['mes' => 'Ya existe un cierre para este banco y mes.'])
                        ->withInput();
                }

                $informacionCierre = CierreMensual::calcularInformacionCierre($banco_id, $mes);

                $cierre = CierreMensual::create([
                    'banco_id' => $banco_id,
                    'ultimo_movimiento_id' => $informacionCierre['ultimo_movimiento_id'],
                    'fecha_cierre' => $fechaCierre,
                    'saldo_final' => $informacionCierre['saldo_final'],
                    'total_ingresos' => $informacionCierre['total_ingresos'],
                    'total_egresos' => $informacionCierre['total_egresos'],
                    'cantidad_movimientos' => $informacionCierre['cantidad_movimientos'],
                    'observaciones' => $request->observaciones,
                    'cerrado' => true,
                    'user_id' => $user_id
                ]);

                DB::commit();

                return redirect()->route('cierres-mensuales.index')
                    ->with('success', 'Cierre individual realizado exitosamente.');
            } else {
                $bancos = Banco::where('activo', true)->get();
                $cierresCreados = 0;

                foreach ($bancos as $banco) {
                    $existeCierre = CierreMensual::where('banco_id', $banco->id)
                        ->whereYear('fecha_cierre', $fechaCierre->year)
                        ->whereMonth('fecha_cierre', $fechaCierre->month)
                        ->exists();

                    if (!$existeCierre) {
                        $informacionCierre = CierreMensual::calcularInformacionCierre($banco->id, $mes);

                        CierreMensual::create([
                            'banco_id' => $banco->id,
                            'ultimo_movimiento_id' => $informacionCierre['ultimo_movimiento_id'],
                            'fecha_cierre' => $fechaCierre,
                            'saldo_final' => $informacionCierre['saldo_final'],
                            'total_ingresos' => $informacionCierre['total_ingresos'],
                            'total_egresos' => $informacionCierre['total_egresos'],
                            'cantidad_movimientos' => $informacionCierre['cantidad_movimientos'],
                            'observaciones' => $request->observaciones . ' (Cierre consolidado)',
                            'cerrado' => true,
                            'user_id' => $user_id
                        ]);

                        $cierresCreados++;
                    }
                }

                DB::commit();

                if ($cierresCreados > 0) {
                    return redirect()->route('cierres-mensuales.index')
                        ->with('success', "Cierre consolidado realizado. Se crearon {$cierresCreados} cierres.");
                } else {
                    return redirect()->back()
                        ->withErrors(['mes' => 'Todos los bancos ya tienen cierre para este mes.'])
                        ->withInput();
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error al realizar cierre: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['error' => 'Error al realizar el cierre: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function generarConsolidadoPdf(Request $request)
    {
        $request->validate([
            'mes' => 'required|date_format:Y-m'
        ]);

        try {
            // Obtener la configuración de empresa
            $empresaConfig = EmpresaConfig::getConfig();
            
            $consolidado = CierreMensual::generarConsolidado($request->mes);
            
            // Agregar la configuración de empresa al array de datos
            $consolidado['empresaConfig'] = $empresaConfig;
            $consolidado['fecha_reporte'] = now()->format('d/m/Y H:i');
            
            $pdf = \PDF::loadView('reportes.pdf.consolidado-cierre', $consolidado);
            
            $nombreArchivo = 'consolidado-cierre-' . $request->mes . '.pdf';
            
            return $pdf->download($nombreArchivo);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al generar el PDF: ' . $e->getMessage()]);
        }
    }

    public function generarPdfIndividual($id)
    {
        $cierreMensual = CierreMensual::findOrFail($id);
        
        $movimientos = Movimiento::where('banco_id', $cierreMensual->banco_id)
            ->whereYear('fecha', $cierreMensual->fecha_cierre->year)
            ->whereMonth('fecha', $cierreMensual->fecha_cierre->month)
            ->with('tipoMovimiento')
            ->orderBy('fecha')
            ->orderBy('id')
            ->get();

        // Obtener la configuración de empresa
        $empresaConfig = EmpresaConfig::getConfig();

        $data = [
            'cierreMensual' => $cierreMensual,
            'movimientos' => $movimientos,
            'fecha_reporte' => now()->format('d/m/Y H:i'),
            'total_ingresos' => $movimientos->sum('monto_debe'),
            'total_egresos' => $movimientos->sum('monto_haber'),
            'empresaConfig' => $empresaConfig, // <-- Agregar configuración de empresa
        ];

        $pdf = \PDF::loadView('reportes.pdf.cierre-individual', $data);
        
        $nombreArchivo = 'cierre-' . $cierreMensual->banco->nombre . '-' . $cierreMensual->fecha_cierre->format('Y-m') . '.pdf';
        
        return $pdf->download($nombreArchivo);
    }

    public function verificarCierreExistente(Request $request)
    {
        $request->validate([
            'banco_id' => 'nullable|exists:bancos,id',
            'mes' => 'required|date_format:Y-m'
        ]);

        try {
            $fecha = Carbon::createFromFormat('Y-m', $request->mes);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Formato de mes inválido',
                'existe' => false
            ], 400);
        }
        
        if ($request->banco_id) {
            $cierreExistente = CierreMensual::where('banco_id', $request->banco_id)
                ->whereYear('fecha_cierre', $fecha->year)
                ->whereMonth('fecha_cierre', $fecha->month)
                ->exists();
                
            return response()->json([
                'existe' => $cierreExistente,
                'tipo' => 'individual'
            ]);
        } else {
            $bancos = Banco::where('activo', true)->get();
            $cierresExistentes = [];
            
            foreach ($bancos as $banco) {
                $existe = CierreMensual::where('banco_id', $banco->id)
                    ->whereYear('fecha_cierre', $fecha->year)
                    ->whereMonth('fecha_cierre', $fecha->month)
                    ->exists();
                    
                $cierresExistentes[$banco->id] = [
                    'nombre' => $banco->nombre,
                    'existe' => $existe
                ];
            }
            
            return response()->json([
                'existe' => count(array_filter($cierresExistentes, fn($item) => $item['existe'])) > 0,
                'detalles' => $cierresExistentes,
                'tipo' => 'consolidado'
            ]);
        }
    }

    public function calcularCierre(Request $request)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'mes' => 'required|date_format:Y-m'
        ]);

        try {
            $infoCierre = CierreMensual::calcularInformacionCierre($request->banco_id, $request->mes);

            return response()->json([
                'success' => true,
                'data' => $infoCierre
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function calcularConsolidado(Request $request)
    {
        $request->validate([
            'mes' => 'required|date_format:Y-m'
        ]);

        try {
            $consolidado = CierreMensual::generarConsolidado($request->mes);

            return response()->json([
                'success' => true,
                'data' => $consolidado
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}