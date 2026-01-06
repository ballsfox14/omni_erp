<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cierre Mensual - {{ $cierreMensual->banco->nombre }} - {{ $cierreMensual->fecha_cierre->format('F Y') }} - {{ $empresaConfig->nombre_empresa ?? 'Omnivision' }}</title>
    <style>
        @page {
            margin: 40px 25px;
            header: html_header;
            footer: html_footer;
        }
        
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000000;
            margin: 0;
            padding: 0;
        }
        
        .header {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        
        .company-logo {
            max-height: 60px;
            max-width: 200px;
            height: auto;
            width: auto;
            object-fit: contain;
            margin-bottom: 10px;
        }
        
        .company-name {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 18pt;
            font-weight: 700;
            color: #000;
            margin: 0;
            text-align: center;
            letter-spacing: -0.5px;
        }
        
        .report-title {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 14pt;
            font-weight: 600;
            text-align: center;
            color: #333;
            margin: 10px 0 5px 0;
            letter-spacing: -0.3px;
        }
        
        .invoice-info {
            margin-bottom: 15px;
            overflow: hidden;
        }
        
        .invoice-header {
            background: #f5f5f5;
            padding: 8px 10px;
            font-weight: 600;
            border: 1px solid #ddd;
            font-size: 9pt;
            text-align: center;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }
        
        .invoice-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .invoice-row {
            display: table-row;
        }
        
        .invoice-cell {
            display: table-cell;
            padding: 6px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
            width: 50%;
        }
        
        .invoice-label {
            font-weight: 600;
            color: #333;
            font-size: 9pt;
            margin-bottom: 2px;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }
        
        .invoice-value {
            font-size: 9pt;
            color: #000;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
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
        
        .income .summary-value {
            color: #28a745;
        }
        
        .expense .summary-value {
            color: #dc3545;
        }
        
        .balance .summary-value {
            color: #007bff;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9pt;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }
        
        table thead th {
            background: #2c3e50;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #1a252f;
            font-size: 8.5pt;
            letter-spacing: 0.3px;
        }
        
        table tbody td {
            padding: 5px 8px;
            border: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        
        .type-badge {
            padding: 1px 6px;
            border-radius: 1px;
            font-size: 7.5pt;
            font-weight: 600;
            display: inline-block;
            line-height: 1.2;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }
        
        .type-ingreso {
            background: #e8f5e8;
            color: #2d5a2d;
        }
        
        .type-egreso {
            background: #faeaea;
            color: #8b3a3a;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            text-align: center;
            font-size: 8pt;
            color: #666;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }
        
        .page-number {
            font-weight: 600;
        }
        
        .observations {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 9pt;
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
    </style>
</head>
<body>
    <!-- Header que se repetirá en cada página -->
    <htmlpageheader name="header">
        <div class="header">
            @php
                $logoPath = null;
                $empresaNombre = $empresaConfig->nombre_empresa ?? 'OMNIVISION';
                if (!empty($empresaConfig->logo_path)) {
                    $logoFullPath = storage_path('app/public/' . $empresaConfig->logo_path);
                    if (file_exists($logoFullPath)) {
                        $logoPath = $logoFullPath;
                    }
                }
            @endphp
            
            @if($logoPath)
            <div style="text-align: center; margin-bottom: 10px;">
                <img src="file://{{ str_replace('\\', '/', $logoPath) }}" 
                     alt="{{ $empresaNombre }}"
                     class="company-logo">
            </div>
            @endif
            
            <div class="company-name">{{ $empresaNombre }}</div>
            <div class="report-title">REPORTE DE CIERRE MENSUAL</div>
            
            @if(isset($empresaConfig) && ($empresaConfig->direccion || $empresaConfig->telefono || $empresaConfig->rnc))
            <div style="text-align: center; font-size: 8pt; color: #666; margin-top: 5px;">
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
        </div>
    </htmlpageheader>
    
    <!-- Información del Cierre -->
    <div class="invoice-info">
        <div class="invoice-header">INFORMACIÓN DEL CIERRE</div>
        
        <div class="invoice-grid">
            <div class="invoice-row">
                <div class="invoice-cell">
                    <div class="invoice-label">DATOS DEL BANCO</div>
                    <div style="margin-top: 5px;">
                        <div><strong>Banco:</strong> {{ $cierreMensual->banco->nombre }}</div>
                        <div><strong>Cuenta:</strong> {{ $cierreMensual->banco->numero_cuenta ?: 'No especificado' }}</div>
                        <div><strong>Moneda:</strong> {{ $cierreMensual->banco->moneda ?? 'PEN' }}</div>
                        <div><strong>Propietario:</strong> {{ $cierreMensual->banco->nombre_propietario ?: 'No especificado' }}</div>
                    </div>
                </div>
                
                <div class="invoice-cell">
                    <div class="invoice-label">DETALLES DEL CIERRE</div>
                    <div style="margin-top: 5px;">
                        <div><strong>Mes:</strong> {{ $cierreMensual->fecha_cierre->translatedFormat('F Y') }}</div>
                        <div><strong>Fecha de Cierre:</strong> {{ $cierreMensual->fecha_cierre->format('d/m/Y') }}</div>
                        <div><strong>Cerrado por:</strong> {{ $cierreMensual->usuario->name ?? 'Usuario del Sistema' }}</div>
                        <div><strong>Estado:</strong> <span style="font-weight: 600; color: {{ $cierreMensual->cerrado ? '#28a745' : '#dc3545' }};">
                            {{ $cierreMensual->cerrado ? 'CERRADO' : 'ABIERTO' }}
                        </span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Resumen Financiero -->
    <div class="summary-grid">
        <div class="summary-card income">
            <div class="summary-title">TOTAL INGRESOS</div>
            <div class="summary-value">${{ number_format($cierreMensual->total_ingresos, 2) }}</div>
            <div style="margin-top: 5px; font-size: 9pt; color: #666;">
                {{ $movimientos->where('monto_debe', '>', 0)->count() }} movimientos
            </div>
        </div>
        
        <div class="summary-card expense">
            <div class="summary-title">TOTAL EGRESOS</div>
            <div class="summary-value">${{ number_format($cierreMensual->total_egresos, 2) }}</div>
            <div style="margin-top: 5px; font-size: 9pt; color: #666;">
                {{ $movimientos->where('monto_haber', '>', 0)->count() }} movimientos
            </div>
        </div>
        
        <div class="summary-card balance">
            <div class="summary-title">SALDO FINAL</div>
            <div class="summary-value">${{ number_format($cierreMensual->saldo_final, 2) }}</div>
            <div style="margin-top: 5px; font-size: 9pt; color: #666;">
                {{ $cierreMensual->cantidad_movimientos }} movimientos totales
            </div>
        </div>
    </div>
    
    <!-- Observaciones -->
    @if($cierreMensual->observaciones)
    <div class="observations">
        <strong>OBSERVACIONES:</strong> {{ $cierreMensual->observaciones }}
    </div>
    @endif
    
    <!-- Detalle de movimientos -->
    <h3 style="margin-bottom: 10px; color: #2c3e50; border-bottom: 1px solid #dee2e6; padding-bottom: 5px; font-size: 12pt; font-weight: 600;">
        DETALLE DE MOVIMIENTOS ({{ $movimientos->count() }})
    </h3>
    
    @if($movimientos->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="12%">Fecha</th>
                    <th width="28%">Concepto</th>
                    <th width="15%">Tipo</th>
                    <th width="15%">Referencia</th>
                    <th width="15%" class="text-right">Debe</th>
                    <th width="15%" class="text-right">Haber</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $movimiento)
                <tr>
                    <td>{{ $movimiento->fecha->format('d/m/Y') }}</td>
                    <td>
                        {{ $movimiento->concepto }}
                        @if($movimiento->observaciones)
                        <div style="font-size: 8pt; color: #666; margin-top: 2px; font-style: italic;">
                            {{ $movimiento->observaciones }}
                        </div>
                        @endif
                    </td>
                    <td>
                        <span class="type-badge type-{{ $movimiento->tipoMovimiento->tipo }}">
                            {{ $movimiento->tipoMovimiento->nombre }}
                        </span>
                    </td>
                    <td>{{ $movimiento->referencia ?: '-' }}</td>
                    <td class="text-right" style="color: #28a745; font-weight: 600;">
                        @if($movimiento->monto_debe > 0)
                            ${{ number_format($movimiento->monto_debe, 2) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right" style="color: #dc3545; font-weight: 600;">
                        @if($movimiento->monto_haber > 0)
                            ${{ number_format($movimiento->monto_haber, 2) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right" style="font-weight: 600; padding: 8px; background: #f0f0f0;">
                        <strong>TOTALES:</strong>
                    </td>
                    <td class="text-right" style="font-weight: 600; color: #28a745; background: #f0f0f0;">
                        ${{ number_format($total_ingresos, 2) }}
                    </td>
                    <td class="text-right" style="font-weight: 600; color: #dc3545; background: #f0f0f0;">
                        ${{ number_format($total_egresos, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
        
        <!-- Resumen adicional -->
        <div class="info-grid">
            <div class="info-card">
                <h3>Resumen de Movimientos</h3>
                <div style="margin-top: 8px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Primer Movimiento:</span>
                        <span>{{ $movimientos->first()->fecha->format('d/m/Y') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Último Movimiento:</span>
                        <span>{{ $movimientos->last()->fecha->format('d/m/Y') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Saldo Inicial:</span>
                        <span>${{ number_format($movimientos->first()->saldo_anterior, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-weight: 600;">
                        <span>Saldo Final:</span>
                        <span>${{ number_format($movimientos->last()->saldo_posterior, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <h3>Información Adicional</h3>
                <div style="margin-top: 8px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>ID de Cierre:</span>
                        <span>#{{ str_pad($cierreMensual->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Fecha Creación:</span>
                        <span>{{ $cierreMensual->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Diferencia Neta:</span>
                        <span style="font-weight: 600; color: {{ ($total_ingresos - $total_egresos) >= 0 ? '#28a745' : '#dc3545' }};">
                            ${{ number_format($total_ingresos - $total_egresos, 2) }}
                        </span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Días con Movimiento:</span>
                        <span>{{ $movimientos->groupBy(function($item) { return $item->fecha->format('Y-m-d'); })->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 30px; color: #6c757d; font-style: italic; border: 1px dashed #dee2e6; background: #f8f9fa;">
            <p><strong>No hay movimientos registrados para este mes.</strong></p>
            <p>El cierre se realizó sin movimientos en el mes.</p>
        </div>
    @endif
    
    <!-- Footer que se repetirá en cada página -->
    <htmlpagefooter name="footer">
        <div class="footer">
            <div class="page-number">Página {PAGENO} de {nbpg}</div>
            @php
                $footerText = $empresaConfig->footer_text ?? 'Este documento fue generado automáticamente por el Sistema de Gestión Bancaria ' . ($empresaConfig->nombre_empresa ?? 'Omnivision') . '.';
            @endphp
            <div>{{ $footerText }}</div>
            <div>Documento válido como reporte contable. Fecha de generación: {{ $fecha_reporte }}</div>
            <div>ID del Reporte: CI-{{ $cierreMensual->id }}-{{ now()->format('YmdHis') }}</div>
        </div>
    </htmlpagefooter>
</body>
</html>