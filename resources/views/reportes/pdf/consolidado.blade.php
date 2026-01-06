<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Consolidado Bancario - {{ $empresaConfig->nombre_empresa ?? 'Omnivision' }}</title>
    <style>
        @page {
            margin: 40px 25px;
            header: html_header;
            footer: html_footer;
            size: landscape;
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
        
        /* Encabezado */
        .header {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        
        .company-logo {
            max-height: 50px;
            max-width: 180px;
            height: auto;
            width: auto;
            object-fit: contain;
            margin-bottom: 5px;
        }
        
        .company-name {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 16pt;
            font-weight: 700;
            color: #000;
            margin: 0;
            text-align: center;
            letter-spacing: -0.5px;
            word-wrap: break-word;
            line-height: 1.1;
        }
        
        .company-name-long {
            font-size: 14pt;
            line-height: 1;
        }
        
        .report-title {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 12pt;
            font-weight: 600;
            text-align: center;
            color: #333;
            margin: 8px 0 3px 0;
            letter-spacing: -0.3px;
        }
        
        /* Información de la Empresa */
        .company-info {
            font-size: 7pt;
            color: #666;
            text-align: center;
            margin-bottom: 10px;
            line-height: 1.1;
        }
        
        /* Información del Reporte - Estilo Factura */
        .invoice-info {
            margin-bottom: 12px;
            overflow: hidden;
        }
        
        .invoice-header {
            background: #f5f5f5;
            padding: 6px 8px;
            font-weight: 600;
            border: 1px solid #ddd;
            font-size: 8pt;
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
            padding: 5px 6px;
            border: 1px solid #ddd;
            vertical-align: top;
            width: 50%;
        }
        
        .invoice-label {
            font-weight: 600;
            color: #333;
            font-size: 8pt;
            margin-bottom: 2px;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }
        
        .invoice-value {
            font-size: 8pt;
            color: #000;
        }
        
        .invoice-value.highlight {
            font-weight: 600;
            color: #d9534f;
        }
        
        /* Tabla Consolidada - ESTILO COMPACTO Y MINIMALISTA */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 8pt;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }
        
        table thead th {
            background: #2c3e50;
            color: white;
            padding: 5px 6px;
            text-align: center;
            font-weight: 600;
            border: 1px solid #1a252f;
            font-size: 7.5pt;
            letter-spacing: 0.3px;
        }
        
        table tbody td {
            padding: 4px 6px;
            border: 1px solid #e0e0e0;
            text-align: center;
            vertical-align: middle;
        }
        
        .banco-header {
            background: #f5f5f5;
            font-weight: 600;
            text-align: left;
        }
        
        .total-general {
            background: #e3e3e3;
            font-weight: 700;
            border-top: 2px solid #2c3e50;
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
        
        /* Resumen */
        .summary-section {
            border: 1px solid #333;
            padding: 10px;
            background: #f9f9f9;
            margin-top: 15px;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }
        
        .summary-title {
            font-weight: 600;
            margin-bottom: 8px;
            text-align: center;
            font-size: 9pt;
            letter-spacing: 0.5px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }
        
        .summary-item {
            text-align: center;
            padding: 6px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 2px;
        }
        
        .summary-value {
            font-size: 10pt;
            font-weight: 700;
            color: #2c3e50;
        }
        
        .summary-value.positive {
            color: #008000;
        }
        
        .summary-value.negative {
            color: #cc0000;
        }
        
        .summary-label {
            font-size: 7.5pt;
            color: #666;
            margin-top: 2px;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            font-size: 7pt;
            color: #666;
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }
        
        .page-number {
            font-weight: 600;
        }
        
        /* Estilos para datos vacíos */
        .empty-data {
            color: #999;
            font-style: italic;
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
            @php
                // Obtener información de empresa
                $empresaNombre = $empresaConfig->nombre_empresa ?? 'OMNIVISION';
                $logoUrl = null;
                
                if(isset($empresaConfig) && !empty($empresaConfig->logo_path)) {
                    $logoPath = storage_path('app/public/' . $empresaConfig->logo_path);
                    if(file_exists($logoPath)) {
                        $logoUrl = $logoPath;
                    }
                }
            @endphp
            
            @if($logoUrl)
            <div style="text-align: center; margin-bottom: 5px;">
                <img src="file://{{ str_replace('\\', '/', $logoUrl) }}" 
                     alt="{{ $empresaNombre }}"
                     class="company-logo">
            </div>
            @endif
            
            <div class="company-name {{ strlen($empresaNombre) > 15 ? 'company-name-long' : '' }}">
                {{ $empresaNombre }}
            </div>
            <div class="report-title">REPORTE CONSOLIDADO BANCARIO</div>
            
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
                    <div class="invoice-label">DATOS DEL CONSOLIDADO</div>
                    <div style="margin-top: 3px;">
                        <div><strong>Total Bancos:</strong> {{ count($consolidado) }}</div>
                        <div><strong>Movimientos Totales:</strong> {{ $totales['movimientos_count'] }}</div>
                        <div><strong>Período Analizado:</strong> <span class="invoice-value highlight">{{ date('d/m/Y', strtotime($fecha_inicio)) }} al {{ date('d/m/Y', strtotime($fecha_fin)) }}</span></div>
                    </div>
                </div>
                
                <div class="invoice-cell">
                    <div class="invoice-label">DETALLES DEL REPORTE</div>
                    <div style="margin-top: 3px;">
                        <div><strong>Generado:</strong> 
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
                        <div><strong>Referencia:</strong> REP-CONS-{{ date('Ymd') }}</div>
                        <div><strong>Estado:</strong> Consolidado General</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla Consolidada -->
    <table>
        <thead>
            <tr>
                <th rowspan="2">Banco</th>
                <th rowspan="2">Cuenta</th>
                <th rowspan="2">Saldo Inicial</th>
                <th colspan="2">Movimientos del Período</th>
                <th rowspan="2">Saldo Final</th>
                <th rowspan="2"># Movimientos</th>
            </tr>
            <tr>
                <th>Ingresos</th>
                <th>Egresos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consolidado as $item)
            <tr>
                <td class="banco-header">{{ $item['banco']->nombre }}</td>
                <td>{{ $item['banco']->numero_cuenta ?: 'No especificado' }}</td>
                <td>${{ number_format($item['saldo_inicial'], 2) }}</td>
                <td class="ingreso">${{ number_format($item['total_ingresos'], 2) }}</td>
                <td class="egreso">${{ number_format($item['total_egresos'], 2) }}</td>
                <td><strong>${{ number_format($item['saldo_final'], 2) }}</strong></td>
                <td>{{ $item['movimientos_count'] }}</td>
            </tr>
            @endforeach
            
            <!-- Totales generales -->
            <tr class="total-general">
                <td colspan="2"><strong>TOTALES GENERALES</strong></td>
                <td><strong>${{ number_format($totales['saldo_inicial'], 2) }}</strong></td>
                <td class="ingreso"><strong>${{ number_format($totales['ingresos'], 2) }}</strong></td>
                <td class="egreso"><strong>${{ number_format($totales['egresos'], 2) }}</strong></td>
                <td><strong>${{ number_format($totales['saldo_final'], 2) }}</strong></td>
                <td><strong>{{ $totales['movimientos_count'] }}</strong></td>
            </tr>
        </tbody>
    </table>
    
    <!-- Resumen Financiero -->
    <div class="summary-section">
        <div class="summary-title">RESUMEN FINANCIERO CONSOLIDADO</div>
        
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-value">${{ number_format($totales['saldo_inicial'], 2) }}</div>
                <div class="summary-label">Saldo Inicial Total</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-value positive">${{ number_format($totales['ingresos'], 2) }}</div>
                <div class="summary-label">Total Ingresos</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-value negative">${{ number_format($totales['egresos'], 2) }}</div>
                <div class="summary-label">Total Egresos</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-value">${{ number_format($totales['saldo_final'], 2) }}</div>
                <div class="summary-label">Saldo Final Total</div>
            </div>
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