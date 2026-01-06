<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Consolidado - {{ $mes }} - {{ $empresaConfig->nombre_empresa ?? 'OmniERP' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .company-logo {
            max-height: 60px;
            max-width: 200px;
            height: auto;
            width: auto;
            object-fit: contain;
            margin-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        
        .header p {
            margin: 5px 0;
            color: #7f8c8d;
        }
        
        .company-info {
            font-size: 9px;
            color: #666;
            text-align: center;
            margin-bottom: 10px;
            line-height: 1.2;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            background: #fff;
            border: 1px solid #dee2e6;
        }
        
        .summary-title {
            font-size: 12px;
            color: #495057;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .summary-value {
            font-size: 18px;
            font-weight: bold;
        }
        
        .total-ingresos .summary-value {
            color: #28a745;
        }
        
        .total-egresos .summary-value {
            color: #dc3545;
        }
        
        .total-movimientos .summary-value {
            color: #007bff;
        }
        
        .saldo-final .summary-value {
            color: #6f42c1;
        }
        
        .bank-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 10px;
        }
        
        .bank-table th,
        .bank-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        
        .bank-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        
        .bank-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    @php
        // Obtener información de empresa
        $empresaNombre = $empresaConfig->nombre_empresa ?? 'OmniERP';
        $logoUrl = null;
        
        if(isset($empresaConfig) && !empty($empresaConfig->logo_path)) {
            $logoPath = storage_path('app/public/' . $empresaConfig->logo_path);
            if(file_exists($logoPath)) {
                $logoUrl = $logoPath;
            }
        }
    @endphp
    
    <div class="header">
        @if($logoUrl)
        <div>
            <img src="file://{{ str_replace('\\', '/', $logoUrl) }}" 
                 alt="{{ $empresaNombre }}"
                 class="company-logo">
        </div>
        @endif
        
        <h1>REPORTE CONSOLIDADO DE CIERRES MENSUALES</h1>
        <p>Sistema de Gestión Bancaria - {{ $empresaNombre }}</p>
        
        @if(isset($empresaConfig) && ($empresaConfig->direccion || $empresaConfig->telefono || $empresaConfig->rnc))
        <div class="company-info">
            @if($empresaConfig->direccion)
            <div>{{ $empresaConfig->direccion }}</div>
            @endif
            @if($empresaConfig->telefono)
            <div>Tel: {{ $empresaConfig->telefono }}</div>
            @endif
            @if($empresaConfig->rnc)
            <div>RNC: {{ $empresaConfig->rnc }}</div>
            @endif
        </div>
        @endif
        
        <p>Mes: {{ $mes }}</p>
        <p>Generado el: {{ $fecha_reporte ?? now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary-grid">
        <div class="summary-card total-ingresos">
            <div class="summary-title">TOTAL INGRESOS</div>
            <div class="summary-value">${{ number_format($totales['total_ingresos'], 2) }}</div>
        </div>
        
        <div class="summary-card total-egresos">
            <div class="summary-title">TOTAL EGRESOS</div>
            <div class="summary-value">${{ number_format($totales['total_egresos'], 2) }}</div>
        </div>
        
        <div class="summary-card total-movimientos">
            <div class="summary-title">TOTAL MOVIMIENTOS</div>
            <div class="summary-value">{{ $totales['total_movimientos'] }}</div>
        </div>
        
        <div class="summary-card saldo-final">
            <div class="summary-title">SALDO FINAL TOTAL</div>
            <div class="summary-value">${{ number_format($totales['saldo_final'], 2) }}</div>
        </div>
    </div>

    <h3 style="margin-bottom: 15px; color: #2c3e50; border-bottom: 1px solid #dee2e6; padding-bottom: 5px;">
        DETALLE POR BANCO ({{ count($consolidado) }} bancos)
    </h3>
    
    <table class="bank-table">
        <thead>
            <tr>
                <th>Banco</th>
                <th class="text-right">Saldo Final</th>
                <th class="text-right">Total Ingresos</th>
                <th class="text-right">Total Egresos</th>
                <th class="text-center">Movimientos</th>
                <th>Último Movimiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consolidado as $item)
            <tr>
                <td>{{ $item['banco']->nombre }}</td>
                <td class="text-right" style="font-weight: 600; color: {{ $item['saldo_final'] >= 0 ? '#28a745' : '#dc3545' }};">
                    ${{ number_format($item['saldo_final'], 2) }}
                </td>
                <td class="text-right" style="color: #28a745;">
                    ${{ number_format($item['total_ingresos'], 2) }}
                </td>
                <td class="text-right" style="color: #dc3545;">
                    ${{ number_format($item['total_egresos'], 2) }}
                </td>
                <td class="text-center">
                    {{ $item['cantidad_movimientos'] }}
                </td>
                <td>
                    @if($item['ultimo_movimiento'])
                        {{ $item['ultimo_movimiento']->fecha->format('d/m/Y') }}
                    @else
                        Sin movimientos
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align: right;">TOTALES:</th>
                <th class="text-right" style="color: {{ $totales['saldo_final'] >= 0 ? '#28a745' : '#dc3545' }};">
                    ${{ number_format($totales['saldo_final'], 2) }}
                </th>
                <th class="text-right" style="color: #28a745;">
                    ${{ number_format($totales['total_ingresos'], 2) }}
                </th>
                <th class="text-right" style="color: #dc3545;">
                    ${{ number_format($totales['total_egresos'], 2) }}
                </th>
                <th class="text-center">
                    {{ $totales['total_movimientos'] }}
                </th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        @php
            $footerText = $empresaConfig->footer_text ?? 'Este documento fue generado automáticamente por el Sistema de Gestión Bancaria ' . $empresaNombre . '.';
        @endphp
        <p>{{ $footerText }}</p>
        <p>Reporte consolidado para el mes de {{ $mes }} - Período: {{ $fecha_inicio }} al {{ $fecha_fin }}</p>
        <p>ID del Reporte: CC-{{ now()->format('YmdHis') }}</p>
    </div>
</body>
</html>