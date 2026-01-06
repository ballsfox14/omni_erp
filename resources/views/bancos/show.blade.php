@extends('layouts.app')

@section('title', 'Detalle del Banco')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <span class="material-symbols-outlined mr-2">
                        account_balance
                    </span>
                    {{ $banco->nombre }}
                </h2>
                <p class="text-gray-600 flex items-center">
                    <span class="material-symbols-outlined mr-1 text-sm">
                        info
                    </span>
                    Detalles completos del banco
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('bancos.edit', $banco) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                    <span class="material-symbols-outlined mr-2">
                        edit
                    </span>
                    Editar
                </a>
                <a href="{{ route('bancos.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                    <span class="material-symbols-outlined mr-2">
                        arrow_back
                    </span>
                    Volver
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Información del Banco -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="material-symbols-outlined mr-2">
                        account_balance_wallet
                    </span>
                    Información del Banco
                </h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <span class="material-symbols-outlined mr-2 text-gray-500">
                            credit_card
                        </span>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Número de Cuenta:</span>
                            <p class="text-gray-900">{{ $banco->numero_cuenta }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <span class="material-symbols-outlined mr-2 text-gray-500">
                            person
                        </span>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Propietario:</span>
                            <p class="text-gray-900 font-medium">{{ $banco->nombre_propietario }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <span class="material-symbols-outlined mr-2 text-gray-500">
                            start
                        </span>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Saldo Inicial:</span>
                            <p class="text-gray-900 font-medium">${{ number_format($banco->saldo_inicial, 2) }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <span class="material-symbols-outlined mr-2 text-gray-500">
                            monetization_on
                        </span>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Saldo Actual:</span>
                            <p class="text-xl font-bold {{ $banco->saldo_actual >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                ${{ number_format($banco->saldo_actual, 2) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <span class="material-symbols-outlined mr-2 text-gray-500">
                            toggle_on
                        </span>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Estado:</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                  {{ $banco->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 14px;">
                                    {{ $banco->activo ? 'check_circle' : 'cancel' }}
                                </span>
                                {{ $banco->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <span class="material-symbols-outlined mr-2 text-gray-500">
                            description
                        </span>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Descripción:</span>
                            <p class="text-gray-900">{{ $banco->descripcion }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="material-symbols-outlined mr-2">
                        analytics
                    </span>
                    Estadísticas
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-green-600">
                                trending_up
                            </span>
                            <span class="text-sm font-medium text-gray-700">Total Ingresos</span>
                        </div>
                        <span class="text-lg font-bold text-green-600">
                            ${{ number_format($totalIngresos, 2) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-red-600">
                                trending_down
                            </span>
                            <span class="text-sm font-medium text-gray-700">Total Egresos</span>
                        </div>
                        <span class="text-lg font-bold text-red-600">
                            ${{ number_format($totalEgresos, 2) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-blue-600">
                            receipt_long
                            </span>
                            <span class="text-sm font-medium text-gray-700">Número de Movimientos</span>
                        </div>
                        <span class="text-lg font-bold text-blue-600">
                            {{ $totalMovimientos }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Movimientos Recientes -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <span class="material-symbols-outlined mr-2">
                        list_alt
                    </span>
                    Movimientos Recientes
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                    event
                                </span>
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                    description
                                </span>
                                Concepto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                    category
                                </span>
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px; color: #38a169;">
                                    trending_up
                                </span>
                                Debe
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px; color: #e53e3e;">
                                    trending_down
                                </span>
                                Haber
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                    account_balance
                                </span>
                                Saldo Posterior
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($movimientos as $movimiento)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-2 text-gray-400">
                                        event
                                    </span>
                                    {{ $movimiento->fecha->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-2 text-gray-400">
                                        description
                                    </span>
                                    {{ $movimiento->concepto }}
                                </div>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($movimiento->monto_debe > 0)
                                <div class="flex items-center text-green-600 font-medium">
                                    <span class="material-symbols-outlined mr-1">
                                        trending_up
                                    </span>
                                    ${{ number_format($movimiento->monto_debe, 2) }}
                                </div>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($movimiento->monto_haber > 0)
                                <div class="flex items-center text-red-600 font-medium">
                                    <span class="material-symbols-outlined mr-1">
                                        trending_down
                                    </span>
                                    ${{ number_format($movimiento->monto_haber, 2) }}
                                </div>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium 
                                {{ $movimiento->saldo_posterior >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-1">
                                        account_balance
                                    </span>
                                    ${{ number_format($movimiento->saldo_posterior, 2) }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <div class="py-8 flex flex-col items-center">
                                    <span class="material-symbols-outlined text-gray-400 text-4xl mb-2">
                                        receipt_long
                                    </span>
                                    No hay movimientos registrados
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($movimientos->count() > 0)
            <div class="px-6 py-4 border-t border-gray-200">
                <a href="{{ route('movimientos.index', ['banco_id' => $banco->id]) }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    <span class="material-symbols-outlined mr-1">
                        list_alt
                    </span>
                    Ver todos los movimientos →
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection