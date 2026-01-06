@extends('layouts.app')

@section('title', 'Reporte Consolidado')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="material-symbols-outlined mr-2">assessment</span>
                Reporte Consolidado
            </h2>
            <p class="text-gray-600 flex items-center">
                <span class="material-symbols-outlined mr-1 text-sm">
                    table_chart
                </span>
                Reporte consolidado de todos los bancos
            </p>
        </div>

        <!-- Formulario de Filtros -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <form action="{{ route('reportes.consolidado.generar') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                
                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <span class="material-symbols-outlined mr-1 text-sm">calendar_month</span>
                        Fecha Inicio
                    </label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" 
                           value="{{ old('fecha_inicio', request('fecha_inicio', now()->startOfMonth()->format('Y-m-d'))) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('fecha_inicio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <span class="material-symbols-outlined mr-1 text-sm">event</span>
                        Fecha Fin
                    </label>
                    <input type="date" name="fecha_fin" id="fecha_fin" 
                           value="{{ old('fecha_fin', request('fecha_fin', now()->endOfMonth()->format('Y-m-d'))) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('fecha_fin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center w-full justify-center">
                        <span class="material-symbols-outlined mr-2">search</span> Ver Reporte
                    </button>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" name="format" value="pdf" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center w-full justify-center">
                        <span class="material-symbols-outlined mr-2">picture_as_pdf</span> PDF
                    </button>
                </div>
            </form>
        </div>

        @if(isset($reportePorBanco))
        <!-- Resultados del Reporte -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Encabezado -->
            <div class="bg-blue-50 border-b border-blue-200 p-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="material-symbols-outlined mr-2">table_chart</span>
                            Reporte Consolidado de Bancos
                        </h3>
                        <p class="text-gray-600 flex items-center">
                            <span class="material-symbols-outlined mr-1 text-sm">
                                calendar_month
                            </span>
                            Período: {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} al 
                                     {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 flex items-center justify-end">
                            <span class="material-symbols-outlined mr-1 text-sm">schedule</span>
                            Generado el: {{ now()->format('d/m/Y H:i') }}
                        </p>
                        
                        <!-- Botones de PDF -->
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 mt-2">
                            <form action="{{ route('reportes.consolidado.generar') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="fecha_inicio" value="{{ $fecha_inicio }}">
                                <input type="hidden" name="fecha_fin" value="{{ $fecha_fin }}">
                                <input type="hidden" name="format" value="pdf">
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center justify-center w-full sm:w-auto">
                                    <span class="material-symbols-outlined mr-2">download</span> Descargar PDF
                                </button>
                            </form>
                            
                            <form action="{{ route('reportes.consolidado.generar') }}" method="POST" class="inline" target="_blank">
                                @csrf
                                <input type="hidden" name="fecha_inicio" value="{{ $fecha_inicio }}">
                                <input type="hidden" name="fecha_fin" value="{{ $fecha_fin }}">
                                <input type="hidden" name="format" value="pdf">
                                <input type="hidden" name="preview" value="true">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center w-full sm:w-auto">
                                    <span class="material-symbols-outlined mr-2">preview</span> Vista Previa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen General -->
            <div class="p-6 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-sm text-green-600 flex items-center">
                            <span class="material-symbols-outlined mr-1 text-sm">trending_up</span>
                            Total Ingresos Consolidados
                        </p>
                        <p class="text-2xl font-bold text-green-700">${{ number_format($totalIngresosConsolidado, 2) }}</p>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm text-red-600 flex items-center">
                            <span class="material-symbols-outlined mr-1 text-sm">trending_down</span>
                            Total Egresos Consolidados
                        </p>
                        <p class="text-2xl font-bold text-red-700">${{ number_format($totalEgresosConsolidado, 2) }}</p>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <p class="text-sm text-purple-600 flex items-center">
                            <span class="material-symbols-outlined mr-1 text-sm">account_balance_wallet</span>
                            Saldo Neto Consolidado
                        </p>
                        <p class="text-2xl font-bold {{ $saldoNetoConsolidado >= 0 ? 'text-green-700' : 'text-red-700' }}">
                            ${{ number_format($saldoNetoConsolidado, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tabla Consolidada por Banco -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                    account_balance
                                </span>
                                Banco
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                    start
                                </span>
                                Saldo Inicial
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px; color: #38a169;">
                                    trending_up
                                </span>
                                Ingresos
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px; color: #e53e3e;">
                                    trending_down
                                </span>
                                Egresos
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                    monetization_on
                                </span>
                                Saldo Final
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                    receipt_long
                                </span>
                                Movimientos
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reportePorBanco as $reporte)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 flex items-center">
                                    <span class="material-symbols-outlined mr-1 text-gray-400">
                                        account_balance
                                    </span>
                                    {{ $reporte['banco']->nombre }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $reporte['banco']->numero_cuenta }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1 text-gray-400">
                                        start
                                    </span>
                                    ${{ number_format($reporte['saldo_inicial'], 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-green-600 font-medium">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1">
                                        trending_up
                                    </span>
                                    ${{ number_format($reporte['total_ingresos'], 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-red-600 font-medium">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1">
                                        trending_down
                                    </span>
                                    ${{ number_format($reporte['total_egresos'], 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium 
                                {{ $reporte['saldo_final'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1">
                                        monetization_on
                                    </span>
                                    ${{ number_format($reporte['saldo_final'], 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1 text-gray-400">
                                        receipt_long
                                    </span>
                                    {{ $reporte['movimientos_count'] }}
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        <!-- Totales Consolidados -->
                        <tr class="bg-gray-100 font-bold">
                            <td class="px-6 py-4 text-sm text-gray-900 flex items-center">
                                <span class="material-symbols-outlined mr-1">
                                    calculate
                                </span>
                                TOTALES CONSOLIDADOS
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-green-600">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1">
                                        trending_up
                                    </span>
                                    ${{ number_format($totalIngresosConsolidado, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-red-600">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1">
                                        trending_down
                                    </span>
                                    ${{ number_format($totalEgresosConsolidado, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm {{ $saldoNetoConsolidado >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1">
                                        monetization_on
                                    </span>
                                    ${{ number_format($saldoNetoConsolidado, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1">
                                        receipt_long
                                    </span>
                                    {{ $totalMovimientosConsolidado }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Detalle por Tipo de Movimiento -->
            <div class="p-6 border-t border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="material-symbols-outlined mr-2">category</span>
                    Desglose por Tipo de Movimiento
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="text-md font-medium text-gray-700 mb-2 flex items-center">
                            <span class="material-symbols-outlined mr-1 text-green-600">trending_up</span>
                            Ingresos
                        </h5>
                        <div class="space-y-2">
                            @foreach($tiposMovimiento->where('tipo', 'ingreso') as $tipo)
                            @if(isset($movimientosPorTipo[$tipo->id]))
                            <div class="flex justify-between items-center p-2 hover:bg-green-50 rounded">
                                <span class="text-sm text-gray-600 flex items-center">
                                    <span class="material-symbols-outlined mr-1 text-sm">
                                        category
                                    </span>
                                    {{ $tipo->nombre }}
                                </span>
                                <span class="text-sm font-medium text-green-600">
                                    ${{ number_format($movimientosPorTipo[$tipo->id], 2) }}
                                </span>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h5 class="text-md font-medium text-gray-700 mb-2 flex items-center">
                            <span class="material-symbols-outlined mr-1 text-red-600">trending_down</span>
                            Egresos
                        </h5>
                        <div class="space-y-2">
                            @foreach($tiposMovimiento->where('tipo', 'egreso') as $tipo)
                            @if(isset($movimientosPorTipo[$tipo->id]))
                            <div class="flex justify-between items-center p-2 hover:bg-red-50 rounded">
                                <span class="text-sm text-gray-600 flex items-center">
                                    <span class="material-symbols-outlined mr-1 text-sm">
                                        category
                                    </span>
                                    {{ $tipo->nombre }}
                                </span>
                                <span class="text-sm font-medium text-red-600">
                                    ${{ number_format($movimientosPorTipo[$tipo->id], 2) }}
                                </span>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie del Reporte -->
            <div class="p-6 border-t border-gray-200 bg-gray-50">
                <div class="text-sm text-gray-500">
                    <p class="flex items-start">
                        <span class="material-symbols-outlined mr-1 mt-0.5 text-sm">info</span>
                        <strong>Resumen:</strong> Este reporte consolida la información de todos los bancos activos.
                    </p>
                    <p class="mt-1 flex items-start">
                        <span class="material-symbols-outlined mr-1 mt-0.5 text-sm">account_balance</span>
                        Total de bancos: {{ count($reportePorBanco) }}
                    </p>
                    <p class="flex items-start">
                        <span class="material-symbols-outlined mr-1 mt-0.5 text-sm">receipt_long</span>
                        Total de movimientos en el período: {{ $totalMovimientosConsolidado }}
                    </p>
                    <p class="mt-2 flex items-start">
                        <span class="material-symbols-outlined mr-1 mt-0.5 text-sm">paid</span>
                        Saldo consolidado inicial: ${{ number_format($saldoInicialConsolidado, 2) }}
                    </p>
                    <p class="flex items-start">
                        <span class="material-symbols-outlined mr-1 mt-0.5 text-sm">account_balance_wallet</span>
                        Saldo consolidado final: ${{ number_format($saldoFinalConsolidado, 2) }}
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        font-size: 11px;
    }
    
    .shadow-md {
        box-shadow: none !important;
    }
    
    .rounded-lg {
        border-radius: 0 !important;
    }
    
    .border {
        border-width: 1px !important;
    }
}
</style>
@endsection