@extends('layouts.app')

@section('title', 'Detalle del Cierre Mensual')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header con botón de regreso -->
        <div class="mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Detalle del Cierre Mensual</h2>
                    <p class="text-gray-600">Información completa del cierre y movimientos del mes</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('cierres-mensuales.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                        <span class="material-symbols-outlined mr-2">
                            arrow_back
                        </span>
                        Volver a Cierres
                    </a>
                    
                    <!-- Botón para generar PDF -->
                    <a href="{{ route('cierres-mensuales.generar-pdf', $cierreMensual->id) }}" 
                       target="_blank"
                       class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                        <span class="material-symbols-outlined mr-2">
                            picture_as_pdf
                        </span>
                        Generar PDF
                    </a>
                </div>
            </div>
            
            <!-- Breadcrumb -->
            <div class="flex items-center text-sm text-gray-500 mt-4">
                <a href="{{ route('cierres-mensuales.index') }}" class="text-blue-600 hover:text-blue-800">Cierres Mensuales</a>
                <span class="material-symbols-outlined mx-2 text-xs">chevron_right</span>
                <span>Detalle del Cierre</span>
            </div>
        </div>

        <!-- Alerta de cierre -->
        @if($cierreMensual->cerrado)
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <span class="material-symbols-outlined text-green-400">lock</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        <strong>Mes cerrado:</strong> Este mes está cerrado y no se pueden modificar los movimientos.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Tarjeta de resumen del cierre -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">
                    <span class="material-symbols-outlined align-text-bottom mr-2">
                        summarize
                    </span>
                    Resumen del Cierre
                </h3>
                <span class="px-3 py-1 text-sm rounded-full {{ $cierreMensual->cerrado ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $cierreMensual->cerrado ? 'CERRADO' : 'ABIERTO' }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Banco -->
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500 mb-1">Banco</div>
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-blue-500 mr-2 text-sm">
                            account_balance
                        </span>
                        <div class="font-medium text-gray-800">{{ $cierreMensual->banco->nombre }}</div>
                    </div>
                </div>
                
                <!-- Mes -->
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500 mb-1">Mes Cerrado</div>
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-blue-500 mr-2 text-sm">
                            calendar_month
                        </span>
                        <div class="font-medium text-gray-800">{{ $cierreMensual->fecha_cierre->translatedFormat('F Y') }}</div>
                    </div>
                </div>
                
                <!-- Saldo Final -->
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500 mb-1">Saldo Final</div>
                    <div class="flex items-center">
                        <span class="material-symbols-outlined {{ $cierreMensual->saldo_final >= 0 ? 'text-green-500' : 'text-red-500' }} mr-2 text-sm">
                            account_balance_wallet
                        </span>
                        <div class="font-bold text-lg {{ $cierreMensual->saldo_final >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($cierreMensual->saldo_final, 2) }}
                        </div>
                    </div>
                </div>
                
                <!-- Usuario que cerró -->
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500 mb-1">Cerrado por</div>
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-blue-500 mr-2 text-sm">
                            person
                        </span>
                        <div class="font-medium text-gray-800">{{ $cierreMensual->usuario->name ?? 'Usuario no disponible' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Total Ingresos -->
                <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-sm text-green-600">Total Ingresos</div>
                            <div class="text-2xl font-bold text-green-700">${{ number_format($cierreMensual->total_ingresos, 2) }}</div>
                        </div>
                        <span class="material-symbols-outlined text-green-400">
                            trending_up
                        </span>
                    </div>
                </div>
                
                <!-- Total Egresos -->
                <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-sm text-red-600">Total Egresos</div>
                            <div class="text-2xl font-bold text-red-700">${{ number_format($cierreMensual->total_egresos, 2) }}</div>
                        </div>
                        <span class="material-symbols-outlined text-red-400">
                            trending_down
                        </span>
                    </div>
                </div>
                
                <!-- Cantidad Movimientos -->
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-sm text-blue-600">Movimientos</div>
                            <div class="text-2xl font-bold text-blue-700">{{ $cierreMensual->cantidad_movimientos }}</div>
                        </div>
                        <span class="material-symbols-outlined text-blue-400">
                            receipt_long
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Observaciones -->
            @if($cierreMensual->observaciones)
            <div class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <div class="flex items-start">
                    <span class="material-symbols-outlined text-yellow-500 mr-2 mt-0.5">
                        notes
                    </span>
                    <div>
                        <div class="text-sm font-medium text-yellow-800 mb-1">Observaciones</div>
                        <div class="text-sm text-yellow-700">{{ $cierreMensual->observaciones }}</div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Información adicional -->
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                <div>
                    <span class="font-medium">Fecha de creación:</span> {{ $cierreMensual->created_at->translatedFormat('d \d\e F \d\e Y \a \l\a\s H:i') }}
                </div>
                <div>
                    <span class="font-medium">Última actualización:</span> {{ $cierreMensual->updated_at->translatedFormat('d \d\e F \d\e Y \a \l\a\s H:i') }}
                </div>
            </div>
        </div>

        <!-- Sección de movimientos -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">
                    <span class="material-symbols-outlined align-text-bottom mr-2">
                        list_alt
                    </span>
                    Movimientos del Mes ({{ $movimientos->count() }})
                </h3>
                <div class="text-sm text-gray-500">
                    Mostrando todos los movimientos de {{ $cierreMensual->fecha_cierre->translatedFormat('F Y') }}
                </div>
            </div>
            
            @if($movimientos->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Concepto</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referencia</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Debe</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Haber</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Posterior</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($movimientos as $movimiento)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $movimiento->fecha->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $movimiento->concepto }}</div>
                                    @if($movimiento->observaciones)
                                    <div class="text-xs text-gray-500 mt-1">{{ Str::limit($movimiento->observaciones, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $movimiento->tipoMovimiento->tipo == 'ingreso' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($movimiento->tipoMovimiento->tipo) }}
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">{{ $movimiento->tipoMovimiento->nombre }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $movimiento->referencia ?: '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($movimiento->monto_debe > 0)
                                    <div class="text-sm font-medium text-green-600">${{ number_format($movimiento->monto_debe, 2) }}</div>
                                    @else
                                    <div class="text-sm text-gray-400">-</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($movimiento->monto_haber > 0)
                                    <div class="text-sm font-medium text-red-600">${{ number_format($movimiento->monto_haber, 2) }}</div>
                                    @else
                                    <div class="text-sm text-gray-400">-</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium {{ $movimiento->saldo_posterior >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        ${{ number_format($movimiento->saldo_posterior, 2) }}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Totales:</td>
                                <td class="px-6 py-3 text-sm font-bold text-green-600">
                                    ${{ number_format($movimientos->sum('monto_debe'), 2) }}
                                </td>
                                <td class="px-6 py-3 text-sm font-bold text-red-600">
                                    ${{ number_format($movimientos->sum('monto_haber'), 2) }}
                                </td>
                                <td class="px-6 py-3">
                                    <!-- Celda vacía para mantener estructura -->
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- Resumen de movimientos -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-100 rounded-lg">
                        <div class="text-sm text-gray-600">Primer movimiento</div>
                        <div class="font-medium text-gray-800">
                            {{ $movimientos->first()->fecha->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="p-4 bg-gray-100 rounded-lg">
                        <div class="text-sm text-gray-600">Último movimiento</div>
                        <div class="font-medium text-gray-800">
                            {{ $movimientos->last()->fecha->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="p-4 bg-gray-100 rounded-lg">
                        <div class="text-sm text-gray-600">Diferencia neta</div>
                        <div class="font-bold text-lg {{ ($movimientos->sum('monto_debe') - $movimientos->sum('monto_haber')) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($movimientos->sum('monto_debe') - $movimientos->sum('monto_haber'), 2) }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Estado vacío -->
                <div class="text-center py-12">
                    <span class="material-symbols-outlined text-gray-300 text-6xl mb-4">
                        receipt_long
                    </span>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay movimientos</h3>
                    <p class="text-gray-500 max-w-md mx-auto">
                        No se registraron movimientos para el mes de {{ $cierreMensual->fecha_cierre->translatedFormat('F Y') }}.
                    </p>
                </div>
            @endif
        </div>

        <!-- Acciones adicionales -->
        <div class="mt-6 flex justify-between items-center">
            <div class="text-sm text-gray-500">
                ID del cierre: <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $cierreMensual->id }}</span>
            </div>
            
            <div class="flex space-x-3">
                <!-- Solo mostrar botón eliminar si el usuario tiene permisos y el cierre no es muy antiguo -->
                @if(auth()->user()->can('delete cierres') && $cierreMensual->created_at->diffInDays(now()) < 7)
                <form action="{{ route('cierres-mensuales.destroy', $cierreMensual) }}" method="POST" 
                      onsubmit="return confirmarEliminacion()">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                        <span class="material-symbols-outlined mr-2">
                            delete
                        </span>
                        Eliminar Cierre
                    </button>
                </form>
                @endif
                
                <!-- Botón para volver -->
                <a href="{{ route('cierres-mensuales.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                    <span class="material-symbols-outlined mr-2">
                        arrow_back
                    </span>
                    Volver al listado
                </a>
            </div>
        </div>

        <!-- Nota informativa -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex items-start">
                <span class="material-symbols-outlined text-blue-500 mr-2 mt-0.5">
                    info
                </span>
                <div>
                    <h4 class="text-sm font-medium text-blue-800 mb-1">Información importante</h4>
                    <p class="text-sm text-blue-700">
                        Los movimientos mostrados corresponden exclusivamente al mes cerrado. 
                        Una vez realizado el cierre, los movimientos no pueden ser modificados ni eliminados 
                        para garantizar la integridad de la información contable.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmarEliminacion() {
    return Swal.fire({
        title: '¿Está seguro de eliminar este cierre?',
        text: "Esta acción eliminará permanentemente el registro del cierre. Los movimientos NO serán eliminados.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        allowOutsideClick: false
    }).then((result) => {
        return result.isConfirmed;
    });
}
</script>
@endpush
@endsection