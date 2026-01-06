@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Bienvenida -->
        <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-sm p-6 border border-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">¡Bienvenido a OmniERP, {{ Auth::user()->name }}!</h1>
                    <p class="text-gray-600">Sistema modular de control financiero y gestión bancaria</p>
                    <p class="text-sm text-gray-500 mt-2">
                        <span class="material-symbols-outlined align-text-bottom" style="font-size: 16px;">
                            calendar_today
                        </span>
                        {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}
                    </p>
                </div>
                <div class="hidden md:block">
                    <span class="material-symbols-outlined text-blue-500" style="font-size: 48px">
                        account_balance
                    </span>
                </div>
            </div>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <span class="material-symbols-outlined text-blue-600">
                                account_balance
                            </span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Bancos Activos</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ \App\Models\Banco::where('activo', true)->count() }}</p>
                            <p class="text-sm text-gray-500 mt-1">Total: {{ \App\Models\Banco::count() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('bancos.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                        Ver todos los bancos
                        <span class="material-symbols-outlined ml-1" style="font-size: 16px;">
                            chevron_right
                        </span>
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-lg">
                            <span class="material-symbols-outlined text-green-600">
                                paid
                            </span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Movimientos</h3>
                            <p class="text-3xl font-bold text-green-600">{{ \App\Models\Movimiento::count() }}</p>
                            <p class="text-sm text-gray-500 mt-1">Total registrados</p>
                        </div>
                    </div>
                    <a href="{{ route('movimientos.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center">
                        Ver todos los movimientos
                        <span class="material-symbols-outlined ml-1" style="font-size: 16px;">
                            chevron_right
                        </span>
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <span class="material-symbols-outlined text-purple-600">
                                category
                            </span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Tipos de Movimiento</h3>
                            <p class="text-3xl font-bold text-purple-600">{{ \App\Models\TipoMovimiento::where('activo', true)->count() }}</p>
                            <p class="text-sm text-gray-500 mt-1">Total: {{ \App\Models\TipoMovimiento::count() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('tipos-movimiento.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium flex items-center">
                        Ver todos los tipos
                        <span class="material-symbols-outlined ml-1" style="font-size: 16px;">
                            chevron_right
                        </span>
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <span class="material-symbols-outlined text-orange-600">
                                event_available
                            </span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Cierres Mensuales</h3>
                            <p class="text-3xl font-bold text-orange-600">
                                @php
                                    $totalCierres = \App\Models\CierreMensual::count();
                                @endphp
                                {{ $totalCierres }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">Meses cerrados</p>
                        </div>
                    </div>
                    @if($totalCierres > 0)
                        <a href="{{ route('cierres-mensuales.index') }}" class="text-orange-600 hover:text-orange-800 text-sm font-medium flex items-center">
                            Ver cierres
                            <span class="material-symbols-outlined ml-1" style="font-size: 16px;">
                                chevron_right
                            </span>
                        </a>
                    @else
                        <a href="{{ route('cierres-mensuales.create') }}" class="text-orange-600 hover:text-orange-800 text-sm font-medium flex items-center">
                            Crear primer cierre
                            <span class="material-symbols-outlined ml-1" style="font-size: 16px;">
                                add_circle
                            </span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Saldo total consolidado -->
        <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl shadow-sm p-6 border border-green-100 mb-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                        <span class="material-symbols-outlined mr-2">
                            account_balance_wallet
                        </span>
                        Saldo Consolidado Total
                    </h3>
                    <p class="text-gray-600">Suma de saldos actuales de todos los bancos activos</p>
                </div>
                <div class="mt-4 md:mt-0">
                    @php
                        $saldoConsolidado = \App\Models\Banco::where('activo', true)->sum('saldo_actual');
                    @endphp
                    <p class="text-4xl font-bold {{ $saldoConsolidado >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        ${{ number_format($saldoConsolidado, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Últimos cierres mensuales -->
        @php
            $ultimosCierres = \App\Models\CierreMensual::with(['banco', 'usuario'])
                ->orderBy('fecha_cierre', 'desc')
                ->take(3)
                ->get();
        @endphp
        
        @if($ultimosCierres->count() > 0)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-symbols-outlined mr-2">
                            event_available
                        </span>
                        Últimos Cierres Mensuales
                    </h3>
                    <a href="{{ route('cierres-mensuales.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                        Ver todos
                        <span class="material-symbols-outlined ml-1" style="font-size: 16px;">
                            chevron_right
                        </span>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($ultimosCierres as $cierre)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-center mb-2">
                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                <span class="material-symbols-outlined text-blue-600">
                                    account_balance
                                </span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">{{ $cierre->banco->nombre }}</h4>
                                <p class="text-sm text-gray-600">{{ $cierre->fecha_cierre->translatedFormat('F Y') }}</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="text-lg font-bold {{ $cierre->saldo_final >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                ${{ number_format($cierre->saldo_final, 2) }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Cerrado por: {{ $cierre->usuario ? $cierre->usuario->name : 'N/A' }}
                            </p>
                        </div>
                        <a href="{{ route('cierres-mensuales.show', $cierre) }}" class="mt-3 text-sm text-blue-600 hover:text-blue-800 flex items-center">
                            Ver detalles
                            <span class="material-symbols-outlined ml-1" style="font-size: 16px;">
                                visibility
                            </span>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Últimos movimientos -->
     <!-- Últimos movimientos -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <span class="material-symbols-outlined mr-2">
                    history
                </span>
                Últimos Movimientos
            </h3>
            <a href="{{ route('movimientos.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                Ver todos
                <span class="material-symbols-outlined ml-1" style="font-size: 16px;">
                    chevron_right
                </span>
            </a>
        </div>
        
        @php
            $ultimosMovimientos = \App\Models\Movimiento::with(['banco', 'tipoMovimiento'])
                ->latest()
                ->take(5)
                ->get();
        @endphp
        
        @if($ultimosMovimientos->isEmpty())
            <div class="text-center py-8 text-gray-500">
                <span class="material-symbols-outlined text-4xl mb-2">
                    receipt_long
                </span>
                <p>No hay movimientos registrados aún.</p>
                <a href="{{ route('movimientos.create') }}" class="text-blue-600 hover:text-blue-800 font-medium mt-2 inline-block">
                    Crear primer movimiento
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <span class="material-symbols-outlined table-header-icon mr-1" style="font-size: 16px; vertical-align: middle;">
                                    schedule
                                </span>
                                Fecha
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <span class="material-symbols-outlined table-header-icon mr-1" style="font-size: 16px; vertical-align: middle;">
                                    account_balance
                                </span>
                                Banco
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <span class="material-symbols-outlined table-header-icon mr-1" style="font-size: 16px; vertical-align: middle;">
                                    description
                                </span>
                                Concepto
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <span class="material-symbols-outlined table-header-icon mr-1" style="font-size: 16px; vertical-align: middle;">
                                    category
                                </span>
                                Tipo
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <span class="material-symbols-outlined table-header-icon mr-1" style="font-size: 16px; vertical-align: middle; {{ $ultimosMovimientos->first()->tipoMovimiento->tipo == 'ingreso' ? 'color: #38a169;' : 'color: #e53e3e;' }}">
                                    {{ $ultimosMovimientos->first()->tipoMovimiento->tipo == 'ingreso' ? 'trending_up' : 'trending_down' }}
                                </span>
                                Monto
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <span class="material-symbols-outlined table-header-icon mr-1" style="font-size: 16px; vertical-align: middle;">
                                    account_balance_wallet
                                </span>
                                Saldo
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($ultimosMovimientos as $movimiento)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $movimiento->banco->nombre }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ \Illuminate\Support\Str::limit($movimiento->concepto, 30) }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                      {{ $movimiento->tipoMovimiento->tipo == 'ingreso' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $movimiento->tipoMovimiento->nombre }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium {{ $movimiento->tipoMovimiento->tipo == 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $movimiento->tipoMovimiento->tipo == 'ingreso' ? '+' : '-' }}${{ number_format($movimiento->tipoMovimiento->tipo == 'ingreso' ? $movimiento->monto_debe : $movimiento->monto_haber, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm font-medium {{ $movimiento->saldo_posterior >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                ${{ number_format($movimiento->saldo_posterior, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

        <!-- Acciones rápidas -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('movimientos.create') }}" 
               class="bg-white border border-blue-200 rounded-lg p-6 shadow-sm hover:shadow-md hover:border-blue-300 transition-all duration-200 flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <span class="material-symbols-outlined text-blue-600">
                        add
                    </span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800">Nuevo Movimiento</h4>
                    <p class="text-sm text-gray-600 mt-1">Registrar transacción bancaria</p>
                </div>
            </a>

            <a href="{{ route('bancos.create') }}" 
               class="bg-white border border-green-200 rounded-lg p-6 shadow-sm hover:shadow-md hover:border-green-300 transition-all duration-200 flex items-center">
                <div class="bg-green-100 p-3 rounded-lg mr-4">
                    <span class="material-symbols-outlined text-green-600">
                        account_balance
                    </span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800">Nuevo Banco</h4>
                    <p class="text-sm text-gray-600 mt-1">Agregar nueva cuenta bancaria</p>
                </div>
            </a>

            <a href="{{ route('cierres-mensuales.create') }}" 
               class="bg-white border border-orange-200 rounded-lg p-6 shadow-sm hover:shadow-md hover:border-orange-300 transition-all duration-200 flex items-center">
                <div class="bg-orange-100 p-3 rounded-lg mr-4">
                    <span class="material-symbols-outlined text-orange-600">
                        event_available
                    </span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800">Cerrar Mes</h4>
                    <p class="text-sm text-gray-600 mt-1">Realizar cierre mensual</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection