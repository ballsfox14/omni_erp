@extends('layouts.app')

@section('title', 'Reporte por Banco')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="material-symbols-outlined mr-2">
                    assessment
                </span>
                Reporte por Banco
            </h2>
            <p class="text-gray-600 flex items-center">
                <span class="material-symbols-outlined mr-1 text-sm">
                    description
                </span>
                Generar reporte detallado de movimientos por banco
            </p>
        </div>

        <!-- Formulario de Filtros -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <form action="{{ route('reportes.banco.generar') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                
                <div>
                    <label for="banco_id" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <span class="material-symbols-outlined mr-1 text-sm">
                            account_balance
                        </span>
                        Banco *
                    </label>
                    <select name="banco_id" id="banco_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccione un banco</option>
                        @foreach($bancos as $banco)
                        <option value="{{ $banco->id }}" 
                                {{ old('banco_id', request('banco_id')) == $banco->id ? 'selected' : '' }}>
                            {{ $banco->nombre }}
                        </option>
                        @endforeach
                    </select>
                    @error('banco_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <span class="material-symbols-outlined mr-1 text-sm">
                            event
                        </span>
                        Fecha Inicio *
                    </label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" 
                           value="{{ old('fecha_inicio', request('fecha_inicio')) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('fecha_inicio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <span class="material-symbols-outlined mr-1 text-sm">
                            event_available
                        </span>
                        Fecha Fin *
                    </label>
                    <input type="date" name="fecha_fin" id="fecha_fin" 
                           value="{{ old('fecha_fin', request('fecha_fin')) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('fecha_fin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center w-full justify-center">
                        <span class="material-symbols-outlined mr-2">search</span> Generar Reporte
                    </button>
                </div>
            </form>
        </div>

        @if(isset($banco) && isset($movimientos))
        <!-- Resultados del Reporte -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Encabezado del Reporte -->
            <div class="bg-blue-50 border-b border-blue-200 p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="material-symbols-outlined mr-2">
                                account_balance
                            </span>
                            {{ $banco->nombre }}
                        </h3>
                        <p class="text-gray-600 flex items-center">
                            <span class="material-symbols-outlined mr-1 text-sm">
                                credit_card
                            </span>
                            Número de Cuenta: {{ $banco->numero_cuenta }}
                        </p>
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
                            <span class="material-symbols-outlined mr-1 text-sm">
                                schedule
                            </span>
                            Generado el: {{ now()->format('d/m/Y H:i') }}
                        </p>
                        
                        <!-- Botones de PDF -->
                        <div class="flex space-x-2 mt-2">
                            <form action="{{ route('reportes.banco.generar') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="banco_id" value="{{ $banco->id }}">
                                <input type="hidden" name="fecha_inicio" value="{{ $fecha_inicio }}">
                                <input type="hidden" name="fecha_fin" value="{{ $fecha_fin }}">
                                <input type="hidden" name="format" value="pdf">
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center">
                                    <span class="material-symbols-outlined mr-2">download</span> Descargar PDF
                                </button>
                            </form>
                            
                            <form action="{{ route('reportes.banco.generar') }}" method="POST" class="inline" target="_blank">
                                @csrf
                                <input type="hidden" name="banco_id" value="{{ $banco->id }}">
                                <input type="hidden" name="fecha_inicio" value="{{ $fecha_inicio }}">
                                <input type="hidden" name="fecha_fin" value="{{ $fecha_fin }}">
                                <input type="hidden" name="format" value="pdf">
                                <input type="hidden" name="preview" value="true">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                                    <span class="material-symbols-outlined mr-2">visibility</span> Vista Previa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen -->
            <div class="p-6 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-sm text-green-600 flex items-center">
                            <span class="material-symbols-outlined mr-1 text-sm">
                                start
                            </span>
                            Saldo Inicial
                        </p>
                        <p class="text-2xl font-bold text-green-700">${{ number_format($saldoInicial, 2) }}</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-600 flex items-center">
                            <span class="material-symbols-outlined mr-1 text-sm">
                                trending_up
                            </span>
                            Total Ingresos
                        </p>
                        <p class="text-2xl font-bold text-blue-700">${{ number_format($totalIngresos, 2) }}</p>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm text-red-600 flex items-center">
                            <span class="material-symbols-outlined mr-1 text-sm">
                                trending_down
                            </span>
                            Total Egresos
                        </p>
                        <p class="text-2xl font-bold text-red-700">${{ number_format($totalEgresos, 2) }}</p>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <p class="text-sm text-purple-600 flex items-center">
                            <span class="material-symbols-outlined mr-1 text-sm">
                                monetization_on
                            </span>
                            Saldo Final
                        </p>
                        <p class="text-2xl font-bold {{ $saldoFinal >= 0 ? 'text-green-700' : 'text-red-700' }}">
                            ${{ number_format($saldoFinal, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tabla de Movimientos -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1 text-sm">
                                    event
                                </span>
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1 text-sm">
                                    description
                                </span>
                                Concepto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1 text-sm">
                                    category
                                </span>
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1 text-sm">
                                    tag
                                </span>
                                Referencia
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1 text-sm" style="color: #38a169;">
                                    trending_up
                                </span>
                                Debe
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1 text-sm" style="color: #e53e3e;">
                                    trending_down
                                </span>
                                Haber
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1 text-sm">
                                    account_balance
                                </span>
                                Saldo
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Fila de Saldo Inicial -->
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">SALDO INICIAL</td>
                            <td class="px-6 py-4 text-sm text-gray-900" colspan="3">
                                <span class="material-symbols-outlined mr-1 text-sm">
                                    start
                                </span>
                                Saldo al inicio del período
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                ${{ number_format($saldoInicial, 2) }}
                            </td>
                        </tr>

                        @foreach($movimientos as $movimiento)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1 text-sm text-gray-400">
                                        event
                                    </span>
                                    {{ $movimiento->fecha->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1 text-sm text-gray-400">
                                        description
                                    </span>
                                    {{ $movimiento->concepto }}
                                </div>
                                @if($movimiento->observaciones)
                                <div class="text-xs text-gray-500 mt-1 flex items-start">
                                    <span class="material-symbols-outlined mr-1 text-xs">
                                        chat_bubble
                                    </span>
                                    {{ $movimiento->observaciones }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                      {{ $movimiento->tipoMovimiento->tipo == 'ingreso' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 14px;">
                                        {{ $movimiento->tipoMovimiento->tipo == 'ingreso' ? 'trending_up' : 'trending_down' }}
                                    </span>
                                    {{ $movimiento->tipoMovimiento->nombre }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $movimiento->referencia ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($movimiento->monto_debe > 0)
                                <div class="flex items-center text-green-600 font-medium">
                                    <span class="material-symbols-outlined mr-1 text-sm">
                                        trending_up
                                    </span>
                                    ${{ number_format($movimiento->monto_debe, 2) }}
                                </div>
                                @else
                                -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($movimiento->monto_haber > 0)
                                <div class="flex items-center text-red-600 font-medium">
                                    <span class="material-symbols-outlined mr-1 text-sm">
                                        trending_down
                                    </span>
                                    ${{ number_format($movimiento->monto_haber, 2) }}
                                </div>
                                @else
                                -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium 
                                {{ $movimiento->saldo_posterior >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1 text-sm">
                                        account_balance
                                    </span>
                                    ${{ number_format($movimiento->saldo_posterior, 2) }}
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        <!-- Totales -->
                        <tr class="bg-gray-100 font-medium">
                            <td class="px-6 py-4 text-sm text-gray-900" colspan="4">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1">
                                        calculate
                                    </span>
                                    TOTALES DEL PERÍODO
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-green-600">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1">
                                        trending_up
                                    </span>
                                    ${{ number_format($totalIngresos, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-red-600">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1">
                                        trending_down
                                    </span>
                                    ${{ number_format($totalEgresos, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                        </tr>

                        <!-- Saldo Final -->
                        <tr class="bg-blue-50 font-bold">
                            <td class="px-6 py-4 text-sm text-gray-900" colspan="4">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1">
                                        monetization_on
                                    </span>
                                    SALDO FINAL
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900" colspan="2"></td>
                            <td class="px-6 py-4 text-sm {{ $saldoFinal >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1">
                                        account_balance
                                    </span>
                                    ${{ number_format($saldoFinal, 2) }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pie del Reporte -->
            <div class="p-6 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    <p class="flex items-start">
                        <span class="material-symbols-outlined mr-1 text-sm">
                            info
                        </span>
                        <strong>Nota:</strong> Este reporte muestra todos los movimientos registrados en el período seleccionado.
                    </p>
                    <p class="mt-2 flex items-center">
                        <span class="material-symbols-outlined mr-1 text-sm">
                            receipt_long
                        </span>
                        Total de movimientos: {{ $movimientos->count() }}
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
        font-size: 12px;
    }
    
    .shadow-md {
        box-shadow: none !important;
    }
    
    .rounded-lg {
        border-radius: 0 !important;
    }
}
</style>
@endsection