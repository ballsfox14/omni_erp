<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Banco - {{ $banco->nombre }} - {{ $empresaConfig->nombre_empresa ?? 'Omnivision' }}</title>
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
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Encabezado - Fuente para titulares */
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
        
        /* Información del Reporte - Estilo Factura */
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
        
        .invoice-value.highlight {
            font-weight: 600;
            color: #d9534f;
        }
        
        /* Información de la Empresa */
        .company-info {
            font-size: 8pt;
            color: #666;
            text-align: center;
            margin-bottom: 15px;
            line-height: 1.2;
        }
        
        /* Tabla de Movimientos - ESTILO COMPACTO Y MINIMALISTA */
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
        
        .saldo-inicial {
            background: #f5f5f5;
            font-weight: 600;
        }
        
        .saldo-inicial td {
            padding: 6px 8px;
        }
        
        .total-periodo {
            background: #f0f0f0;
            font-weight: 600;
        }
        
        .total-periodo td {
            padding: 6px 8px;
        }
        
        .saldo-final {
            background: #e3e3e3;
            font-weight: 600;
        }
        
        .saldo-final td {
            padding: 6px 8px;
        }
        
        /* Estilos para montos */
        .ingreso {
            color: #008000;
            font-weight: 600;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }
        
        .egreso {
            color: #cc0000;
            font-weight: 600;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }
        
        /* Badges para tipos - más compactos */
        .badge {
            padding: 1px 6px;
            border-radius: 1px;
            font-size: 7.5pt;
            font-weight: 600;
            display: inline-block;
            line-height: 1.2;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            letter-spacing: 0.2px;
        }
        
        .badge-ingreso {
            background: #e8f5e8;
            color: #2d5a2d;
        }
        
        .badge-egreso {
            background: #faeaea;
            color: #8b3a3a;
        }
        
        /* Resumen */
        .summary-section {
            border: 1px solid #333;
            padding: 12px;
            background: #f9f9f9;
            margin-top: 20px;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }
        
        .summary-title {
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
            font-size: 10pt;
            letter-spacing: 0.5px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        
        .summary-final {
            font-weight: 700;
            font-size: 11pt;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 2px solid #333;
            letter-spacing: -0.2px;
        }
        
        /* Footer */
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
        
        /* Observaciones - más compactas */
        .observations {
            font-size: 7.5pt;
            color: #666;
            margin-top: 1px;
            font-style: italic;
            line-height: 1.1;
        }
        
        /* Estilos para datos vacíos */
        .empty-data {
            color: #999;
            font-style: italic;
        }
        
        /* Mejora para alineación vertical */
        .table-cell-content {
            line-height: 1.2;
        }
        
        /* Estilos consistentes para texto fuerte */
        strong, b {
            font-weight: 600;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }
    </style>
</head>
<body>
    <!-- Header que se repetirá en cada página -->
    <htmlpageheader name="header">
        <div class="header">
            @if(isset($empresaConfig) && $empresaConfig->logo_url)
            <div style="text-align: center; margin-bottom: 10px;">
                <img src="{{ $empresaConfig->logo_url }}" 
                     alt="{{ $empresaConfig->nombre_empresa }}"
                     class="company-logo">
            </div>
            @endif
            
            <div class="company-name">{{ $empresaConfig->nombre_empresa ?? 'OMNIVISION' }}</div>
            <div class="report-title">REPORTE DE MOVIMIENTOS BANCARIOS</div>
            
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
        </div>
    </htmlpageheader>
    
    <!-- Información en formato factura -->
    <div class="invoice-info">
        <div class="invoice-header">INFORMACIÓN DEL REPORTE</div>
        
        <div class="invoice-grid">
            <div class="invoice-row">
                <div class="invoice-cell">
                    <div class="invoice-label">INFORMACIÓN DEL BANCO</div>
                    <div style="margin-top: 5px;">
                        <div><strong>Banco:</strong> {{ $banco->nombre }}</div>
                        <div><strong>Cuenta:</strong> {{ $banco->numero_cuenta ?: 'No especificado' }}</div>
                        <div><strong>Propietario:</strong> {{ $banco->nombre_propietario ?: 'No especificado' }}</div>
                        <div><strong>Moneda:</strong> 
                            @if($banco->moneda)
                                {{ $banco->moneda }}
                            @else
                                <span class="empty-data">No especificada</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="invoice-cell">
                    <div class="invoice-label">DETALLES DEL REPORTE</div>
                    <div style="margin-top: 5px;">
                        <div><strong>Período:</strong> <span class="invoice-value highlight">{{ date('d/m/Y', strtotime($fecha_inicio)) }} al {{ date('d/m/Y', strtotime($fecha_fin)) }}</span></div>
                        <div><strong>Generado:</strong> 
                            @php
                                // Formato simple y seguro para la hora
                                try {
                                    $fecha = new DateTime($fecha_generacion);
                                    $fecha->setTimezone(new DateTimeZone('America/El_Salvador'));
                                    echo $fecha->format('d/m/Y H:i:s');
                                } catch (Exception $e) {
                                    // Si hay error, usar la fecha actual en El Salvador
                                    $fecha = new DateTime('now', new DateTimeZone('America/El_Salvador'));
                                    echo $fecha->format('d/m/Y H:i:s');
                                }
                            @endphp
                        </div>
                        <div><strong>Movimientos:</strong> {{ count($movimientos) }}</div>
                        <div><strong>Referencia:</strong> REP-{{ date('Ymd') }}-{{ strtoupper(substr($banco->nombre, 0, 3)) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla de Movimientos - VERSIÓN COMPACTA -->
    <table>
        <thead>
            <tr>
                <th width="12%">Fecha</th>
                <th width="28%">Concepto</th>
                <th width="15%">Tipo</th>
                <th width="15%">Referencia</th>
                <th width="15%">Monto</th>
                <th width="15%">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <!-- Saldo Inicial -->
            <tr class="saldo-inicial">
                <td colspan="5">SALDO INICIAL</td>
                <td><strong>${{ number_format($saldo_inicial, 2) }}</strong></td>
            </tr>
            
            <!-- Movimientos -->
            @foreach($movimientos as $movimiento)
            <tr>
                <td>{{ date('d/m/Y', strtotime($movimiento->fecha)) }}</td>
                <td class="table-cell-content">
                    {{ $movimiento->concepto }}
                    @if($movimiento->observaciones)
                    <div class="observations">{{ $movimiento->observaciones }}</div>
                    @endif
                </td>
                <td>
                    @if($movimiento->tipoMovimiento->tipo == 'ingreso')
                    <span class="badge badge-ingreso">{{ $movimiento->tipoMovimiento->nombre }}</span>
                    @else
                    <span class="badge badge-egreso">{{ $movimiento->tipoMovimiento->nombre }}</span>
                    @endif
                </td>
                <td>{{ $movimiento->referencia ?? 'N/A' }}</td>
                <td class="{{ $movimiento->tipoMovimiento->tipo == 'ingreso' ? 'ingreso' : 'egreso' }}">
                    @if($movimiento->tipoMovimiento->tipo == 'ingreso')
                    + ${{ number_format($movimiento->monto_debe, 2) }}
                    @else
                    - ${{ number_format($movimiento->monto_haber, 2) }}
                    @endif
                </td>
                <td><strong>${{ number_format($movimiento->saldo_posterior, 2) }}</strong></td>
            </tr>
            @endforeach
            
            <!-- Totales del Período -->
            <tr class="total-periodo">
                <td colspan="4">TOTALES DEL PERÍODO</td>
                <td class="ingreso">+ ${{ number_format($total_ingresos, 2) }}</td>
                <td class="egreso">- ${{ number_format($total_egresos, 2) }}</td>
            </tr>
            
            <!-- Saldo Final -->
            <tr class="saldo-final">
                <td colspan="5">SALDO FINAL</td>
                <td>${{ number_format($saldo_final, 2) }}</td>
            </tr>
        </tbody>
    </table>
    
    <!-- Resumen Financiero -->
    <div class="summary-section">
        <div class="summary-title">RESUMEN FINANCIERO</div>
        
        <div class="summary-row">
            <span>Saldo Inicial:</span>
            <span>${{ number_format($saldo_inicial, 2) }}</span>
        </div>
        
        <div class="summary-row">
            <span>Total Ingresos:</span>
            <span class="ingreso">+ ${{ number_format($total_ingresos, 2) }}</span>
        </div>
        
        <div class="summary-row">
            <span>Total Egresos:</span>
            <span class="egreso">- ${{ number_format($total_egresos, 2) }}</span>
        </div>
        
        <div class="summary-row summary-final">
            <span>SALDO FINAL:</span>
            <span>${{ number_format($saldo_final, 2) }}</span>
        </div>
    </div>
    
    <!-- Footer que se repetirá en cada página -->
    <htmlpagefooter name="footer">
        <div class="footer">
            <div class="page-number">Página {PAGENO} de {nbpg}</div>
            @if(isset($empresaConfig) && $empresaConfig->footer_text)
                <div>{{ $empresaConfig->footer_text }}</div>
            @else
                <div>Reporte generado por {{ $empresaConfig->nombre_empresa ?? 'Omnivision' }} - Sistema de Gestión Financiera</div>
            @endif
            <div>
                @php
                    try {
                        $fecha = new DateTime($fecha_generacion);
                        $fecha->setTimezone(new DateTimeZone('America/El_Salvador'));
                        echo $fecha->format('d/m/Y H:i:s');
                    } catch (Exception $e) {
                        $fecha = new DateTime('now', new DateTimeZone('America/El_Salvador'));
                        echo $fecha->format('d/m/Y H:i:s');
                    }
                @endphp
            </div>
        </div>
    </htmlpagefooter>
</body>
</html>