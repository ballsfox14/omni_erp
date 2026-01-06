@extends('layouts.app')
@section('title', 'Movimientos Bancarios')
@section('content')
    <style>
        :root {
            --primary: #2d3748;
            --secondary: #4a5568;
            --accent: #4299e1;
            --success: #38a169;
            --danger: #e53e3e;
            --background: #f8fafc;
            --card-bg: #ffffff;
            --border: #e2e8f0;
        }

        .financial-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .financial-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .transaction-row {
            border-bottom: 1px solid var(--border);
            transition: background-color 0.15s ease;
        }

        .transaction-row:hover {
            background-color: #f7fafc;
        }

        .amount-positive {
            color: var(--success);
            font-weight: 500;
        }

        .amount-negative {
            color: var(--danger);
            font-weight: 500;
        }

        .status-badge {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
        }

        .status-completed {
            background-color: #c6f6d5;
            color: #22543d;
        }

        .status-pending {
            background-color: #fed7d7;
            color: #742a2a;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7fafc;
            border: 1px solid var(--border);
            color: var(--secondary);
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .action-btn:hover {
            background: white;
            border-color: var(--accent);
            color: var(--accent);
        }

        .section-header {
            border-bottom: 1px solid var(--border);
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7fafc;
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 600;
            color: var(--primary);
            line-height: 1.2;
        }

        .stat-label {
            font-size: 13px;
            color: var(--secondary);
            margin-top: 2px;
        }

        .search-container {
            background: var(--background);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .search-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: white;
            font-size: 14px;
            transition: all 0.15s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(66, 153, 225, 0.1);
        }

        .transaction-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-header {
            background: #f7fafc;
            border-bottom: 1px solid var(--border);
        }

        .table-header th {
            padding: 14px 16px;
            text-align: left;
            font-weight: 500;
            color: var(--secondary);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-cell {
            padding: 16px;
            border-bottom: 1px solid var(--border);
        }

        .primary-btn {
            background: var(--accent);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .primary-btn:hover {
            background: #3182ce;
        }

        .secondary-btn {
            background: white;
            color: var(--secondary);
            border: 1px solid var(--border);
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .secondary-btn:hover {
            background: #f7fafc;
            border-color: var(--accent);
            color: var(--accent);
        }

        .empty-state {
            padding: 48px 20px;
            text-align: center;
            color: var(--secondary);
        }

        .empty-state-icon {
            color: #cbd5e0;
            margin-bottom: 12px;
        }

        .report-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 24px;
        }

        .report-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .report-description {
            color: var(--secondary);
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 20px;
        }

        .date-range {
            background: #f7fafc;
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
        }

        .date-range-label {
            font-size: 11px;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .date-range-value {
            font-size: 14px;
            font-weight: 500;
            color: var(--primary);
        }

        .filter-section {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .table-header-icon {
            font-size: 16px;
            margin-right: 6px;
            vertical-align: middle;
        }

        .table-header-text {
            vertical-align: middle;
        }

        /* Estilos para el encabezado del día */
        .day-header {
            background-color: #f0fff4;
            border-left: 4px solid #38a169;
            padding: 16px;
            margin-bottom: 16px;
            border-radius: 8px;
        }

        .day-title {
            font-size: 18px;
            font-weight: 600;
            color: #22543d;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .day-stats {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
        }

        .day-stat {
            text-align: center;
            min-width: 120px;
        }

        .day-stat-label {
            font-size: 12px;
            color: #718096;
            margin-bottom: 4px;
        }

        .day-stat-value {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
        }

        .day-stat-positive {
            color: #38a169;
        }

        .day-stat-negative {
            color: #e53e3e;
        }

        /* Navegación entre días */
        .day-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background: #f7fafc;
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .day-nav-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--secondary);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .day-nav-btn:hover:not(:disabled) {
            background: #f7fafc;
            border-color: var(--accent);
            color: var(--accent);
        }

        .day-nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .day-info {
            text-align: center;
        }

        .day-current {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary);
        }

        .day-position {
            font-size: 13px;
            color: var(--secondary);
            margin-top: 4px;
        }

        /* Saldo final consolidado */
        .saldo-final-row {
            background-color: #f0fff4;
            border-top: 2px solid #38a169;
            font-weight: 600;
        }

        .saldo-final-label {
            color: #22543d;
        }

        .saldo-final-value {
            color: #22543d;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .table-header th {
                padding: 12px;
                font-size: 11px;
            }

            .table-cell {
                padding: 12px;
            }

            .filter-section {
                flex-direction: column;
            }

            .table-header-icon {
                font-size: 14px;
                margin-right: 4px;
            }

            .day-stats {
                flex-direction: column;
                gap: 12px;
            }

            .day-stat {
                text-align: left;
                display: flex;
                justify-content: space-between;
                min-width: auto;
            }

            .day-navigation {
                flex-direction: column;
                gap: 12px;
            }

            .day-nav-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 24px 20px;">
        <!-- Header -->
        <div style="margin-bottom: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
                <div>
                    <h1 style="font-size: 24px; font-weight: 600; color: var(--primary); margin-bottom: 4px;">
                        Movimientos Bancarios
                    </h1>
                    <p style="color: var(--secondary); font-size: 14px;">
                        Gestión diaria de transacciones financieras
                    </p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('movimientos.create') }}" class="primary-btn">
                        <span class="material-symbols-outlined" style="font-size: 18px;">
                            add
                        </span>
                        Nuevo Movimiento
                    </a>
                    <a href="{{ route('cierres-mensuales.desde-movimientos') }}" class="primary-btn"
                        style="background: #38a169;">
                        <span class="material-symbols-outlined" style="font-size: 18px;">
                            event_available
                        </span>
                        Cierre Mensual
                    </a>
                </div>
            </div>
            <!-- Stats Minimalistas -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #4299e1;">
                        <span class="material-symbols-outlined">
                            account_balance
                        </span>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $bancos->count() }}</div>
                        <div class="stat-label">Bancos Activos</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #38a169;">
                        <span class="material-symbols-outlined">
                            trending_up
                        </span>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">${{ number_format($totalIngresosGeneral, 2) }}</div>
                        <div class="stat-label">Ingresos Totales</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #e53e3e;">
                        <span class="material-symbols-outlined">
                            trending_down
                        </span>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">${{ number_format($totalEgresosGeneral, 2) }}</div>
                        <div class="stat-label">Egresos Totales</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #805ad5;">
                        <span class="material-symbols-outlined">
                            receipt_long
                        </span>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $totalMovimientos }}</div>
                        <div class="stat-label">Total Movimientos</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Search and Filters -->
        <div class="search-container">
            <h2 class="section-header" style="font-size: 18px; font-weight: 600; color: var(--primary);">
                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px; font-size: 20px;">
                    filter_alt
                </span>
                Filtros
            </h2>
            <form id="filterForm" action="{{ route('movimientos.index') }}" method="GET" class="filter-section">
                <!-- Banco Filter -->
                <div style="flex: 1; min-width: 200px;">
                    <label
                        style="display: block; margin-bottom: 6px; font-size: 13px; font-weight: 500; color: var(--secondary);">
                        <span class="material-symbols-outlined"
                            style="font-size: 16px; vertical-align: middle; margin-right: 4px;">
                            account_balance
                        </span>
                        Banco
                    </label>
                    <select name="banco_id" class="search-input">
                        <option value="">Todos los bancos</option>
                        @foreach ($bancos as $banco)
                            <option value="{{ $banco->id }}" {{ request('banco_id') == $banco->id ? 'selected' : '' }}>
                                {{ $banco->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Tipo Filter -->
                <div style="flex: 1; min-width: 200px;">
                    <label
                        style="display: block; margin-bottom: 6px; font-size: 13px; font-weight: 500; color: var(--secondary);">
                        <span class="material-symbols-outlined"
                            style="font-size: 16px; vertical-align: middle; margin-right: 4px;">
                            category
                        </span>
                        Tipo
                    </label>
                    <select name="tipo_movimiento_id" class="search-input">
                        <option value="">Todos los tipos</option>
                        @foreach ($tiposMovimiento as $tipo)
                            <option value="{{ $tipo->id }}" {{ request('tipo_movimiento_id') == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Date Filters -->
                <div style="display: flex; gap: 12px; flex: 2; min-width: 300px;">
                    <div style="flex: 1;">
                        <label
                            style="display: block; margin-bottom: 6px; font-size: 13px; font-weight: 500; color: var(--secondary);">
                            <span class="material-symbols-outlined"
                                style="font-size: 16px; vertical-align: middle; margin-right: 4px;">
                                calendar_month
                            </span>
                            Fecha Inicio
                        </label>
                        <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="search-input">
                    </div>
                    <div style="flex: 1;">
                        <label
                            style="display: block; margin-bottom: 6px; font-size: 13px; font-weight: 500; color: var(--secondary);">
                            <span class="material-symbols-outlined"
                                style="font-size: 16px; vertical-align: middle; margin-right: 4px;">
                                event
                            </span>
                            Fecha Fin
                        </label>
                        <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="search-input">
                    </div>
                </div>
                <!-- Filter Actions -->
                <div
                    style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border); width: 100%;">
                    <div style="color: var(--secondary); font-size: 13px;">
                        <span class="material-symbols-outlined"
                            style="font-size: 16px; vertical-align: middle; margin-right: 4px;">
                            search
                        </span>
                        {{ $diasPaginados->total() }} días encontrados
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="primary-btn">
                            <span class="material-symbols-outlined" style="font-size: 18px;">
                                filter_alt
                            </span>
                            Filtrar
                        </button>
                        <a href="{{ route('movimientos.index') }}" class="secondary-btn">
                            <span class="material-symbols-outlined" style="font-size: 18px;">
                                refresh
                            </span>
                            Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <!-- Los mensajes de sesión ahora se muestran automáticamente con SweetAlert2 desde el layout -->
        
        <!-- Navegación entre días - SOLO UNA VEZ (arriba) -->
        @if ($diasPaginados->count() > 0 && $diasPaginados->total() > 0)
            <div class="day-navigation">
                @if ($diasPaginados->currentPage() > 1)
                    <a href="{{ $diasPaginados->previousPageUrl() }}&{{ http_build_query(request()->except(['page'])) }}"
                        class="day-nav-btn">
                        <span class="material-symbols-outlined">
                            arrow_back
                        </span>
                        Día Anterior
                    </a>
                @else
                    <button class="day-nav-btn" disabled>
                        <span class="material-symbols-outlined">
                            arrow_back
                        </span>
                        Día Anterior
                    </button>
                @endif

                <div class="day-info">
                    <div class="day-current">
                        Día {{ $diasPaginados->firstItem() }} de {{ $diasPaginados->total() }}:
                        @if ($diasPaginados->count() > 0)
                            {{ \Carbon\Carbon::parse($diasPaginados[0]->dia)->format('d/m/Y') }}
                        @endif
                    </div>
                    <div class="day-position">
                        Página {{ $diasPaginados->currentPage() }} de {{ $diasPaginados->lastPage() }}
                    </div>
                </div>

                @if ($diasPaginados->hasMorePages())
                    <a href="{{ $diasPaginados->nextPageUrl() }}&{{ http_build_query(request()->except(['page'])) }}"
                        class="day-nav-btn">
                        Día Siguiente
                        <span class="material-symbols-outlined">
                            arrow_forward
                        </span>
                    </a>
                @else
                    <button class="day-nav-btn" disabled>
                        Día Siguiente
                        <span class="material-symbols-outlined">
                            arrow_forward
                        </span>
                    </button>
                @endif
            </div>
        @endif

        <!-- Transactions Table -->
        <div class="financial-card" style="margin-bottom: 32px; overflow: hidden;">
            <div style="padding: 20px 20px 0 20px;">
                <h2 class="section-header" style="font-size: 18px; font-weight: 600; color: var(--primary);">
                    <span class="material-symbols-outlined"
                        style="vertical-align: middle; margin-right: 8px; font-size: 20px;">
                        table_chart
                    </span>
                    Movimientos del Día
                </h2>
            </div>

            @if ($movimientosPorDia->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="transaction-table">
                        <thead class="table-header">
                            <tr>
                                <th>
                                    <span class="material-symbols-outlined table-header-icon">
                                        schedule
                                    </span>
                                    <span class="table-header-text">Hora</span>
                                </th>
                                <th>
                                    <span class="material-symbols-outlined table-header-icon">
                                        account_balance
                                    </span>
                                    <span class="table-header-text">Banco</span>
                                </th>
                                <th>
                                    <span class="material-symbols-outlined table-header-icon">
                                        description
                                    </span>
                                    <span class="table-header-text">Concepto</span>
                                </th>
                                <th>
                                    <span class="material-symbols-outlined table-header-icon">
                                        category
                                    </span>
                                    <span class="table-header-text">Tipo</span>
                                </th>
                                <th>
                                    <span class="material-symbols-outlined table-header-icon" style="color: var(--success);">
                                        trending_up
                                    </span>
                                    <span class="table-header-text">Debe</span>
                                </th>
                                <th>
                                    <span class="material-symbols-outlined table-header-icon" style="color: var(--danger);">
                                        trending_down
                                    </span>
                                    <span class="table-header-text">Haber</span>
                                </th>
                                <th>
                                    <span class="material-symbols-outlined table-header-icon">
                                        account_balance_wallet
                                    </span>
                                    <span class="table-header-text">Saldo</span>
                                </th>
                                <th>
                                    <span class="material-symbols-outlined table-header-icon">
                                        settings
                                    </span>
                                    <span class="table-header-text">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movimientosPorDia as $movimiento)
                                <tr class="transaction-row">
                                    <td class="table-cell">
                                        <div style="font-weight: 500; color: var(--primary);">
                                            {{ $movimiento->created_at->format('h:i A') }}
                                        </div>
                                        <div style="font-size: 12px; color: var(--secondary); margin-top: 2px;">
                                            {{ $movimiento->fecha->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <div style="font-weight: 500; color: var(--primary);">
                                            {{ $movimiento->banco->nombre }}
                                        </div>
                                        @if ($movimiento->banco->numero_cuenta)
                                            <div style="font-size: 12px; color: var(--secondary); margin-top: 2px;">
                                                {{ $movimiento->banco->numero_cuenta }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="table-cell">
                                        <div style="font-weight: 500; color: var(--primary); margin-bottom: 2px;">
                                            {{ Str::limit($movimiento->concepto, 30) }}
                                        </div>
                                        @if ($movimiento->referencia)
                                            <div
                                                style="display: flex; align-items: center; gap: 4px; font-size: 12px; color: var(--secondary);">
                                                <span class="material-symbols-outlined" style="font-size: 12px;">
                                                    tag
                                                </span>
                                                Ref: {{ $movimiento->referencia }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="table-cell">
                                        @if ($movimiento->tipoMovimiento->tipo == 'ingreso')
                                            <span class="status-badge status-completed">{{ $movimiento->tipoMovimiento->nombre }}</span>
                                        @else
                                            <span class="status-badge status-pending">{{ $movimiento->tipoMovimiento->nombre }}</span>
                                        @endif
                                    </td>
                                    <td class="table-cell">
                                        @if ($movimiento->monto_debe > 0)
                                            <div class="amount-positive">${{ number_format($movimiento->monto_debe, 2) }}
                                            </div>
                                        @else
                                            <span style="color: var(--secondary);">-</span>
                                        @endif
                                    </td>
                                    <td class="table-cell">
                                        @if ($movimiento->monto_haber > 0)
                                            <div class="amount-negative">${{ number_format($movimiento->monto_haber, 2) }}
                                            </div>
                                        @else
                                            <span style="color: var(--secondary);">-</span>
                                        @endif
                                    </td>
                                    <td class="table-cell">
                                        <div
                                            style="font-weight: 600; color: {{ $movimiento->saldo_posterior >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                                            ${{ number_format($movimiento->saldo_posterior, 2) }}
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <div style="display: flex; gap: 6px;">
                                            <a href="{{ route('movimientos.edit', $movimiento) }}" class="action-btn"
                                                title="Editar">
                                                <span class="material-symbols-outlined" style="font-size: 16px;">
                                                    edit
                                                </span>
                                            </a>
                                            <form action="{{ route('movimientos.destroy', $movimiento) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="action-btn delete-movimiento-btn"
                                                    data-concepto="{{ $movimiento->concepto }}"
                                                    data-fecha="{{ $movimiento->created_at->format('d/m/Y H:i') }}"
                                                    title="Eliminar">
                                                    <span class="material-symbols-outlined" style="font-size: 16px;">
                                                        delete
                                                    </span>
                                                </button>
                                            </form>
                                            <button onclick="showMovimientoDetails({{ $movimiento->id }})" class="action-btn"
                                                title="Ver detalles">
                                                <span class="material-symbols-outlined" style="font-size: 16px;">
                                                    visibility
                                                </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Resumen del día (debajo de la tabla) -->
                @if (request('banco_id') && $diasPaginados->count() > 0)
                    <div class="day-header" style="margin-top: 24px;">
                        <div class="day-title">
                            <span class="material-symbols-outlined">
                                summarize
                            </span>
                            Resumen del Día {{ \Carbon\Carbon::parse($diasPaginados[0]->dia)->format('d/m/Y') }}
                        </div>
                        <div class="day-stats">
                            <div class="day-stat">
                                <div class="day-stat-label">Saldo Inicial</div>
                                <div class="day-stat-value">${{ number_format($totalesPorDia['saldo_inicial_dia'], 2) }}
                                </div>
                            </div>
                            <div class="day-stat">
                                <div class="day-stat-label">Ingresos</div>
                                <div class="day-stat-value day-stat-positive">
                                    ${{ number_format($totalesPorDia['total_debe'], 2) }}</div>
                            </div>
                            <div class="day-stat">
                                <div class="day-stat-label">Egresos</div>
                                <div class="day-stat-value day-stat-negative">
                                    ${{ number_format($totalesPorDia['total_haber'], 2) }}</div>
                            </div>
                            <div class="day-stat">
                                <div class="day-stat-label">Saldo Final</div>
                                <div class="day-stat-value"
                                    style="color: {{ $totalesPorDia['saldo_final_dia'] >= 0 ? '#38a169' : '#e53e3e' }};">
                                    ${{ number_format($totalesPorDia['saldo_final_dia'], 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @elseif($diasPaginados->count() > 0 && $movimientosPorDia->count() == 0)
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <span class="material-symbols-outlined" style="font-size: 48px;">
                            event_busy
                        </span>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 600; color: var(--primary); margin-bottom: 6px;">
                        No hay movimientos en este día
                    </h3>
                    <p style="color: var(--secondary); margin-bottom: 20px; font-size: 14px;">
                        No se encontraron movimientos para el día
                        {{ $diasPaginados->count() > 0 ? \Carbon\Carbon::parse($diasPaginados[0]->dia)->format('d/m/Y') : '' }}
                    </p>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <span class="material-symbols-outlined" style="font-size: 48px;">
                            receipt_long
                        </span>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 600; color: var(--primary); margin-bottom: 6px;">
                        No hay movimientos registrados
                    </h3>
                    <p style="color: var(--secondary); margin-bottom: 20px; font-size: 14px;">
                        Comienza agregando tu primer movimiento bancario
                    </p>
                    <a href="{{ route('movimientos.create') }}" class="primary-btn">
                        <span class="material-symbols-outlined" style="font-size: 18px;">
                            add
                        </span>
                        Crear Primer Movimiento
                    </a>
                </div>
            @endif

            <!-- Nueva paginación simplificada (solo abajo) -->
            @if ($diasPaginados->hasPages() && $diasPaginados->total() > 1)
                <div style="padding: 20px; border-top: 1px solid var(--border);">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="color: var(--secondary); font-size: 13px;">
                            Mostrando día {{ $diasPaginados->firstItem() }} de {{ $diasPaginados->total() }}
                        </div>
                        <div style="display: flex; gap: 6px; align-items: center;">
                            @if ($diasPaginados->onFirstPage())
                                <span class="secondary-btn" style="opacity: 0.5; cursor: not-allowed; padding: 6px 12px;">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">
                                        chevron_left
                                    </span>
                                </span>
                            @else
                                <a href="{{ $diasPaginados->previousPageUrl() }}&{{ http_build_query(request()->except(['page'])) }}"
                                    class="secondary-btn" style="padding: 6px 12px;">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">
                                        chevron_left
                                    </span>
                                </a>
                            @endif

                            @for ($i = 1; $i <= $diasPaginados->lastPage(); $i++)
                                @if ($i == $diasPaginados->currentPage())
                                    <span
                                        style="background-color: var(--accent); color: white; padding: 6px 12px; border-radius: 6px; font-weight: 500; font-size: 13px;">
                                        {{ $i }}
                                    </span>
                                @elseif($i >= $diasPaginados->currentPage() - 2 && $i <= $diasPaginados->currentPage() + 2)
                                    <a href="{{ $diasPaginados->url($i) }}&{{ http_build_query(request()->except(['page'])) }}"
                                        style="padding: 6px 12px; border-radius: 6px; font-weight: 500; font-size: 13px; color: var(--secondary); border: 1px solid var(--border); text-decoration: none; transition: all 0.15s ease;">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor

                            @if ($diasPaginados->hasMorePages())
                                <a href="{{ $diasPaginados->nextPageUrl() }}&{{ http_build_query(request()->except(['page'])) }}"
                                    class="secondary-btn" style="padding: 6px 12px;">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">
                                        chevron_right
                                    </span>
                                </a>
                            @else
                                <span class="secondary-btn" style="opacity: 0.5; cursor: not-allowed; padding: 6px 12px;">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">
                                        chevron_right
                                    </span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Reports Section -->
        <h2 class="section-header" style="font-size: 18px; font-weight: 600; color: var(--primary); margin-bottom: 20px;">
            <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px; font-size: 20px;">
                assessment
            </span>
            Reportes
        </h2>
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px; margin-bottom: 32px;">
            <!-- Reporte por Banco -->
            <div class="report-card">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 16px;">
                    <div
                        style="width: 40px; height: 40px; background: #f7fafc; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #4299e1;">
                        <span class="material-symbols-outlined">
                            account_balance
                        </span>
                    </div>
                    <div>
                        <div class="report-title">Reporte por Banco</div>
                        <div class="report-description">Análisis detallado por institución financiera</div>
                    </div>
                </div>
                @if (request('banco_id'))
                    <div class="date-range">
                        <div class="date-range-label">Banco Seleccionado</div>
                        <div class="date-range-value">
                            @php
                                $bancoSeleccionado = $bancos->firstWhere('id', request('banco_id'));
                            @endphp
                            {{ $bancoSeleccionado ? $bancoSeleccionado->nombre : 'N/A' }}
                        </div>
                    </div>
                    <form action="{{ route('reportes.banco.generar') }}" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="banco_id" value="{{ request('banco_id') }}">
                        <input type="hidden" name="fecha_inicio"
                            value="{{ request('fecha_inicio', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }}">
                        <input type="hidden" name="fecha_fin"
                            value="{{ request('fecha_fin', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')) }}">
                        <input type="hidden" name="format" value="pdf">
                        <button type="submit" class="primary-btn" style="width: 100%;">
                            <span class="material-symbols-outlined" style="font-size: 18px;">
                                download
                            </span>
                            Descargar Reporte PDF
                        </button>
                    </form>
                @else
                    <div style="text-align: center; padding: 24px 0;">
                        <span class="material-symbols-outlined" style="font-size: 40px; color: #cbd5e0; margin-bottom: 12px;">
                            account_balance
                        </span>
                        <p style="color: var(--secondary); margin-bottom: 16px; font-size: 14px;">
                            Selecciona un banco en los filtros para generar el reporte
                        </p>
                        <a href="#filtros" class="secondary-btn" style="font-size: 13px;">
                            <span class="material-symbols-outlined" style="font-size: 16px;">
                                filter_alt
                            </span>
                            Ir a Filtros
                        </a>
                    </div>
                @endif
            </div>
            <!-- Reporte Consolidado -->
            <div class="report-card">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 16px;">
                    <div
                        style="width: 40px; height: 40px; background: #f7fafc; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #38a169;">
                        <span class="material-symbols-outlined">
                            stacked_bar_chart
                        </span>
                    </div>
                    <div>
                        <div class="report-title">Reporte Consolidado</div>
                        <div class="report-description">Visión general de todos los movimientos</div>
                    </div>
                </div>
                <div class="date-range">
                    <div class="date-range-label">Período</div>
                    <div class="date-range-value">
                        @if (request('fecha_inicio') && request('fecha_fin'))
                            {{ \Carbon\Carbon::parse(request('fecha_inicio'))->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse(request('fecha_fin'))->format('d/m/Y') }}
                        @else
                            {{ \Carbon\Carbon::now()->startOfMonth()->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::now()->endOfMonth()->format('d/m/Y') }}
                        @endif
                    </div>
                </div>
                <div style="display: flex; gap: 10px;">
                    <form action="{{ route('reportes.consolidado.generar') }}" method="POST" target="_blank"
                        style="flex: 1;">
                        @csrf
                        <input type="hidden" name="fecha_inicio"
                            value="{{ request('fecha_inicio', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }}">
                        <input type="hidden" name="fecha_fin"
                            value="{{ request('fecha_fin', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')) }}">
                        <input type="hidden" name="format" value="pdf">
                        <button type="submit" class="primary-btn" style="width: 100%;">
                            <span class="material-symbols-outlined" style="font-size: 18px;">
                                picture_as_pdf
                            </span>
                            Descargar PDF
                        </button>
                    </form>
                    <a href="{{ route('reportes.consolidado') }}" class="secondary-btn"
                        style="flex: 1; text-align: center;">
                        <span class="material-symbols-outlined" style="font-size: 18px;">
                            visibility
                        </span>
                        Ver en Web
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para detalles -->
    <div id="movimientoDetailsModal"
        style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
        <div
            style="background: white; border-radius: 12px; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto;">
            <div style="padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <h3 style="font-size: 18px; font-weight: 600; color: var(--primary);">
                        <span class="material-symbols-outlined"
                            style="vertical-align: middle; margin-right: 8px; font-size: 20px;">
                            receipt_long
                        </span>
                        Detalles del Movimiento
                    </h3>
                    <button onclick="closeModal()" class="action-btn">
                        <span class="material-symbols-outlined" style="font-size: 18px;">
                            close
                        </span>
                    </button>
                </div>
                <div id="movimientoDetailsContent">
                    <!-- Contenido dinámico -->
                </div>
                <div
                    style="display: flex; justify-content: flex-end; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
                    <button onclick="closeModal()" class="secondary-btn" style="font-size: 13px;">
                        <span class="material-symbols-outlined" style="font-size: 16px;">
                            close
                        </span>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-completar fechas si están vacías
    const fechaInicio = document.querySelector('input[name="fecha_inicio"]');
    const fechaFin = document.querySelector('input[name="fecha_fin"]');
    if (!fechaInicio.value || !fechaFin.value) {
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        fechaInicio.value = firstDay.toISOString().split('T')[0];
        fechaFin.value = lastDay.toISOString().split('T')[0];
    }

    // Manejar eliminación con SweetAlert2 (mismo diseño que bancos)
    const deleteButtons = document.querySelectorAll('.delete-movimiento-btn');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const concepto = this.getAttribute('data-concepto');
            const fecha = this.getAttribute('data-fecha');
            const form = this.closest('form');
            
            Swal.fire({
                title: '¿Estás seguro?',
                html: `Vas a eliminar el movimiento: <strong>"${concepto}"</strong><br>
                      <small>Registrado el ${fecha}</small><br><br>
                      <span class="text-red-600 text-sm">⚠️ Advertencia: Esta acción no se puede deshacer</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                backdrop: true,
                allowOutsideClick: false,
                allowEscapeKey: true,
                allowEnterKey: false,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    // Usar el formulario existente en lugar de crear uno dinámico
                    return new Promise((resolve) => {
                        form.submit();
                    });
                }
            });
        });
    });

    // Validar que fecha inicio no sea mayor que fecha fin
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function (e) {
            const inicio = new Date(fechaInicio.value);
            const fin = new Date(fechaFin.value);
            if (fechaInicio.value && fechaFin.value && inicio > fin) {
                e.preventDefault();
                Swal.fire({
                    title: 'Error en fechas',
                    text: 'La fecha de inicio no puede ser mayor que la fecha de fin',
                    icon: 'error',
                    confirmButtonColor: '#3085d6'
                });
                fechaInicio.focus();
            }
        });
    }
});

function showMovimientoDetails(id) {
    const content = `
        <div style="display: grid; gap: 20px;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                <div>
                    <div style="font-size: 12px; color: var(--secondary); margin-bottom: 4px;">ID</div>
                    <div style="font-weight: 500; color: var(--primary);">#${id}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: var(--secondary); margin-bottom: 4px;">Estado</div>
                    <span class="status-badge status-completed">Completado</span>
                </div>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--secondary); margin-bottom: 4px;">Descripción</div>
                <div style="color: var(--primary); line-height: 1.5; font-size: 14px;">
                    Aquí se mostrarían los detalles completos del movimiento en una implementación real.
                </div>
            </div>
            <div style="background: #f7fafc; border-radius: 8px; padding: 16px;">
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                    <div>
                        <div style="font-size: 12px; color: var(--secondary); margin-bottom: 4px;">Monto</div>
                        <div style="font-weight: 600; color: var(--success);">$1,250.00</div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--secondary); margin-bottom: 4px;">Fecha</div>
                        <div style="font-weight: 500; color: var(--primary);">15/12/2023</div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--secondary); margin-bottom: 4px;">Referencia</div>
                        <div style="font-weight: 500; color: var(--primary);">REF-123456</div>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.getElementById('movimientoDetailsContent').innerHTML = content;
    document.getElementById('movimientoDetailsModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('movimientoDetailsModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Cerrar modal al hacer clic fuera
document.getElementById('movimientoDetailsModal')?.addEventListener('click', function (e) {
    if (e.target === this) {
        closeModal();
    }
});

// Cerrar modal con ESC
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
@endpush