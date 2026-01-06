<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cierre Mensual - {{ $cierreMensual->banco->nombre }} - {{ $cierreMensual->fecha_cierre->format('F Y') }}</title>
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
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #3498db;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .info-card {
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            background: #f8f9fa;
        }
        
        .info-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        
        .info-value {
            color: #212529;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            background: #fff;
        }
        
        .summary-card.income {
            border-top: 4px solid #28a745;
            background: #f8fff9;
        }
        
        .summary-card.expense {
            border-top: 4px solid #dc3545;
            background: #fff8f9;
        }
        
        .summary-card.balance {
            border-top: 4px solid #007bff;
            background: #f8fbff;
        }
        
        .summary-title {
            font-size: 14px;
            color: #495057;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .summary-value {
            font-size: 24px;
            font-weight: bold;
        }
        
        .income .summary-value {
            color: #28a745;
        }
        
        .expense .summary-value {
            color: #dc3545;
        }
        
        .balance .summary-value {
            color: #007bff;
        }
        
        .movements-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 10px;
        }
        
        .movements-table th,
        .movements-table td {
            border: 1px solid #dee2e6;
            padding: 6px;
            text-align: left;
        }
        
        .movements-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        
        .movements-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .type-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
        }
        
        .type-ingreso {
            background-color: #d4edda;
            color: #155724;
        }
        
        .type-egreso {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 10px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .observations {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .observations h4 {
            margin: 0 0 10px 0;
            color: #856404;
            font-size: 14px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        @php
            // Intentar obtener la configuración de empresa
            $empresaNombre = 'OmniERP';
            $logoUrl = null;
            
            try {
                if(isset($empresaConfig) && $empresaConfig) {
                    $empresaNombre = $empresaConfig->nombre_empresa ?? 'OmniERP';
                    
                    if(!empty($empresaConfig->logo_path)) {
                        $logoPath = storage_path('app/public/' . $empresaConfig->logo_path);
                        if(file_exists($logoPath)) {
                            $logoUrl = $logoPath;
                        }
                    }
                } else {
                    $empresaConfig = \App\Models\EmpresaConfig::getConfig();
                    $empresaNombre = $empresaConfig->nombre_empresa ?? 'OmniERP';
                    
                    if(!empty($empresaConfig->logo_path)) {
                        $logoPath = storage_path('app/public/' . $empresaConfig->logo_path);
                        if(file_exists($logoPath)) {
                            $logoUrl = $logoPath;
                        }
                    }
                }
            } catch (\Exception $e) {
                // Si hay error, usar valores por defecto
            }
        @endphp
        
        @if($logoUrl)
        <div>
            <img src="file://{{ str_replace('\\', '/', $logoUrl) }}" 
                 alt="{{ $empresaNombre }}"
                 class="company-logo">
        </div>
        @endif
        
        <h1>REPORTE DE CIERRE MENSUAL</h1>
        <p>Sistema de Gestión Bancaria - {{ $empresaNombre }}</p>
        <p>Generado el: {{ $fecha_reporte }}</p>
    </div>

    <!-- Información de la empresa/banco -->
    <div class="company-info">
        <div style="text-align: center;">
            <h2 style="margin: 0 0 10px 0; color: #2c3e50;">{{ $cierreMensual->banco->nombre }}</h2>
            <p style="margin: 5px 0;"><strong>Mes:</strong> {{ $cierreMensual->fecha_cierre->translatedFormat('F Y') }}</p>
            <p style="margin: 5px 0;"><strong>Fecha de Cierre:</strong> {{ $cierreMensual->fecha_cierre->format('d/m/Y') }}</p>
            <p style="margin: 5px 0;"><strong>Cerrado por:</strong> {{ $cierreMensual->usuario->name ?? 'Usuario del Sistema' }}</p>
            <p style="margin: 5px 0;"><strong>Estado:</strong> {{ $cierreMensual->cerrado ? 'CERRADO' : 'ABIERTO' }}</p>
        </div>
    </div>

    <!-- Grid de información -->
    <div class="info-grid">
        <div class="info-card">
            <h3>Información del Banco</h3>
            <div class="info-content">
                <div class="info-row">
                    <span class="info-label">Nombre:</span>
                    <span class="info-value">{{ $cierreMensual->banco->nombre }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Moneda:</span>
                    <span class="info-value">{{ $cierreMensual->banco->moneda ?? 'PEN' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Estado:</span>
                    <span class="info-value">{{ $cierreMensual->banco->activo ? 'Activo' : 'Inactivo' }}</span>
                </div>
            </div>
        </div>

        <div class="info-card">
            <h3>Información del Cierre</h3>
            <div class="info-content">
                <div class="info-row">
                    <span class="info-label">ID Cierre:</span>
                    <span class="info-value">#{{ str_pad($cierreMensual->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha Creación:</span>
                    <span class="info-value">{{ $cierreMensual->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Última Actualización:</span>
                    <span class="info-value">{{ $cierreMensual->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de resumen -->
    <div class="summary-grid">
        <div class="summary-card income">
            <div class="summary-title">TOTAL INGRESOS</div>
            <div class="summary-value">${{ number_format($cierreMensual->total_ingresos, 2) }}</div>
            <div style="margin-top: 5px; font-size: 11px; color: #666;">
                {{ number_format($movimientos->where('monto_debe', '>', 0)->count()) }} movimientos
            </div>
        </div>
        
        <div class="summary-card expense">
            <div class="summary-title">TOTAL EGRESOS</div>
            <div class="summary-value">${{ number_format($cierreMensual->total_egresos, 2) }}</div>
            <div style="margin-top: 5px; font-size: 11px; color: #666;">
                {{ number_format($movimientos->where('monto_haber', '>', 0)->count()) }} movimientos
            </div>
        </div>
        
        <div class="summary-card balance">
            <div class="summary-title">SALDO FINAL</div>
            <div class="summary-value">${{ number_format($cierreMensual->saldo_final, 2) }}</div>
            <div style="margin-top: 5px; font-size: 11px; color: #666;">
                {{ $cierreMensual->cantidad_movimientos }} movimientos totales
            </div>
        </div>
    </div>

    <!-- Observaciones -->
    @if($cierreMensual->observaciones)
    <div class="observations">
        <h4>OBSERVACIONES</h4>
        <p>{{ $cierreMensual->observaciones }}</p>
    </div>
    @endif

    <!-- Detalle de movimientos -->
    <h3 style="margin-bottom: 15px; color: #2c3e50; border-bottom: 1px solid #dee2e6; padding-bottom: 5px;">
        DETALLE DE MOVIMIENTOS ({{ $movimientos->count() }})
    </h3>
    
    @if($movimientos->count() > 0)
        <table class="movements-table">
            <thead>
                <tr>
                    <th width="70">Fecha</th>
                    <th>Concepto</th>
                    <th width="80">Tipo</th>
                    <th width="100">Referencia</th>
                    <th width="80" class="text-right">Debe</th>
                    <th width="80" class="text-right">Haber</th>
                    <th width="100" class="text-right">Saldo Posterior</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $saldo_acumulado = 0;
                @endphp
                
                @foreach($movimientos as $movimiento)
                @php
                    if($loop->first) {
                        $saldo_acumulado = $movimiento->saldo_anterior;
                    }
                    $saldo_acumulado += $movimiento->monto_debe - $movimiento->monto_haber;
                @endphp
                <tr>
                    <td>{{ $movimiento->fecha->format('d/m/Y') }}</td>
                    <td>{{ $movimiento->concepto }}</td>
                    <td>
                        <span class="type-badge type-{{ $movimiento->tipoMovimiento->tipo }}">
                            {{ strtoupper($movimiento->tipoMovimiento->tipo) }}
                        </span>
                        <div style="font-size: 9px; color: #666; margin-top: 2px;">
                            {{ $movimiento->tipoMovimiento->nombre }}
                        </div>
                    </td>
                    <td>{{ $movimiento->referencia ?: '-' }}</td>
                    <td class="text-right">
                        @if($movimiento->monto_debe > 0)
                            ${{ number_format($movimiento->monto_debe, 2) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">
                        @if($movimiento->monto_haber > 0)
                            ${{ number_format($movimiento->monto_haber, 2) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">
                        ${{ number_format($movimiento->saldo_posterior, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right" style="font-weight: 600; padding: 8px;">TOTALES:</td>
                    <td class="text-right" style="font-weight: 600; color: #28a745;">
                        ${{ number_format($total_ingresos, 2) }}
                    </td>
                    <td class="text-right" style="font-weight: 600; color: #dc3545;">
                        ${{ number_format($total_egresos, 2) }}
                    </td>
                    <td class="text-right" style="font-weight: 600;">
                        @if($movimientos->last())
                            ${{ number_format($movimientos->last()->saldo_posterior, 2) }}
                        @else
                            $0.00
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Resumen adicional -->
        <div class="info-grid" style="margin-top: 20px;">
            <div class="info-card">
                <h3>Resumen de Movimientos</h3>
                <div class="info-content">
                    <div class="info-row">
                        <span class="info-label">Total Movimientos:</span>
                        <span class="info-value">{{ $movimientos->count() }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Primer Movimiento:</span>
                        <span class="info-value">
                            @if($movimientos->first())
                                {{ $movimientos->first()->fecha->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Último Movimiento:</span>
                        <span class="info-value">
                            @if($movimientos->last())
                                {{ $movimientos->last()->fecha->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Diferencia Neta:</span>
                        <span class="info-value" style="font-weight: 600; color: {{ ($total_ingresos - $total_egresos) >= 0 ? '#28a745' : '#dc3545' }};">
                            ${{ number_format($total_ingresos - $total_egresos, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <h3>Información Adicional</h3>
                <div class="info-content">
                    <div class="info-row">
                        <span class="info-label">Saldo Inicial:</span>
                        <span class="info-value">
                            @if($movimientos->first())
                                ${{ number_format($movimientos->first()->saldo_anterior, 2) }}
                            @else
                                $0.00
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Saldo Final:</span>
                        <span class="info-value">
                            @if($movimientos->last())
                                ${{ number_format($movimientos->last()->saldo_posterior, 2) }}
                            @else
                                ${{ number_format($cierreMensual->saldo_final, 2) }}
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Días con Movimiento:</span>
                        <span class="info-value">
                            {{ $movimientos->groupBy(function($item) { return $item->fecha->format('Y-m-d'); })->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="no-data">
            <p><strong>No hay movimientos registrados para este mes.</strong></p>
            <p>El cierre se realizó sin movimientos en el mes.</p>
        </div>
    @endif

    <!-- Pie de página -->
    <div class="footer">
        @php
            $footerText = $empresaConfig->footer_text ?? 'Este documento fue generado automáticamente por el Sistema de Gestión Bancaria ' . $empresaNombre . '.';
        @endphp
        <p>{{ $footerText }}</p>
        <p>Documento válido como reporte contable. Fecha de generación: {{ $fecha_reporte }}</p>
        <p>ID del Reporte: CI-{{ $cierreMensual->id }}-{{ now()->format('YmdHis') }}</p>
    </div>
</body>
</html>