@extends('layouts.app')

@section('title', 'Cierre Mensual desde Movimientos')

@section('content')
<style>
    .cierre-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    
    .cierre-card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    
    .tipo-cierre-btn {
        width: 100%;
        padding: 20px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }
    
    .tipo-cierre-btn:hover {
        border-color: #4299e1;
        background: #ebf8ff;
    }
    
    .tipo-cierre-btn.active {
        border-color: #4299e1;
        background: #ebf8ff;
    }
    
    .consolidado-info {
        background: #f0fff4;
        border: 1px solid #9ae6b4;
        border-radius: 8px;
        padding: 16px;
    }
    
    .individual-info {
        background: #ebf8ff;
        border: 1px solid #90cdf4;
        border-radius: 8px;
        padding: 16px;
    }
</style>

<div class="container" style="max-width: 1000px; margin: 0 auto; padding: 24px 20px;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
            <div>
                <h1 style="font-size: 24px; font-weight: 600; color: #2d3748; margin-bottom: 4px;">
                    Cierre Mensual
                </h1>
                <p style="color: #4a5568; font-size: 14px;">
                    Realice el cierre de caja desde la vista de movimientos
                </p>
            </div>
            <a href="{{ route('movimientos.index') }}" class="secondary-btn">
                <span class="material-symbols-outlined" style="font-size: 18px;">
                    arrow_back
                </span>
                Volver a Movimientos
            </a>
        </div>
        
        <!-- Ruta de navegación -->
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px; color: #4a5568; font-size: 14px;">
            <a href="{{ route('movimientos.index') }}" style="color: #4299e1;">Movimientos</a>
            <span class="material-symbols-outlined" style="font-size: 16px;">
                chevron_right
            </span>
            <span>Cierre Mensual</span>
        </div>
    </div>

    <!-- Mensajes de error -->
    @if($errors->any())
    <div style="background: #fed7d7; border-left: 4px solid #f56565; padding: 16px; margin-bottom: 24px; border-radius: 8px;">
        <div style="display: flex;">
            <div style="flex-shrink: 0;">
                <span class="material-symbols-outlined" style="color: #f56565;">error</span>
            </div>
            <div style="margin-left: 12px;">
                <h3 style="font-size: 14px; font-weight: 600; color: #c53030;">Error al realizar el cierre</h3>
                <div style="margin-top: 8px; font-size: 13px; color: #9b2c2c;">
                    <ul style="list-style-type: disc; padding-left: 20px; margin-top: 4px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tarjetas de tipo de cierre -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 32px;">
        <div class="tipo-cierre-btn" id="btn-individual" onclick="seleccionarTipo('individual')">
            <div style="text-align: center;">
                <span class="material-symbols-outlined" style="font-size: 40px; color: #4299e1; margin-bottom: 10px;">
                    account_balance
                </span>
                <h3 style="font-weight: 600; color: #2d3748; margin-bottom: 6px;">
                    Cierre Individual
                </h3>
                <p style="color: #4a5568; font-size: 13px;">
                    Realice el cierre para un banco específico
                </p>
            </div>
        </div>
        
        <div class="tipo-cierre-btn" id="btn-consolidado" onclick="seleccionarTipo('consolidado')">
            <div style="text-align: center;">
                <span class="material-symbols-outlined" style="font-size: 40px; color: #38a169; margin-bottom: 10px;">
                    stacked_bar_chart
                </span>
                <h3 style="font-weight: 600; color: #2d3748; margin-bottom: 6px;">
                    Cierre Consolidado
                </h3>
                <p style="color: #4a5568; font-size: 13px;">
                    Realice el cierre para todos los bancos
                </p>
            </div>
        </div>
    </div>

    <!-- Formulario de cierre -->
    <form action="{{ route('cierres-mensuales.realizar') }}" method="POST" id="formCierre">
        @csrf
        
        <input type="hidden" name="tipo_cierre" id="tipoCierre" value="individual">
        
        <div class="cierre-card" style="padding: 24px; margin-bottom: 24px;">
            <h2 style="font-size: 18px; font-weight: 600; color: #2d3748; margin-bottom: 20px;">
                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px;">
                    settings
                </span>
                Configuración del Cierre
            </h2>
            
            <!-- Selección de mes -->
            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 6px; font-size: 13px; font-weight: 500; color: #4a5568;">
                    Mes a cerrar *
                </label>
                <input type="month" name="mes" id="mes" value="{{ $ultimoMes }}" required
                       class="search-input" style="width: 200px;">
                <button type="button" onclick="verificarCierre()" class="secondary-btn" style="margin-left: 10px;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">
                        search
                    </span>
                    Verificar
                </button>
            </div>
            
            <!-- Información del cierre individual -->
            <div id="info-individual" class="individual-info" style="display: none; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                    <span class="material-symbols-outlined" style="color: #4299e1;">
                        info
                    </span>
                    <span style="font-weight: 500; color: #2d3748;">Cierre por Banco</span>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 6px; font-size: 13px; font-weight: 500; color: #4a5568;">
                        Seleccionar Banco *
                    </label>
                    <select name="banco_id" id="banco_id" class="search-input">
                        <option value="">Seleccione un banco</option>
                        @foreach($bancos as $banco)
                        <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div id="resultado-verificacion" style="margin-top: 12px;"></div>
            </div>
            
            <!-- Información del cierre consolidado -->
            <div id="info-consolidado" class="consolidado-info" style="display: none; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                    <span class="material-symbols-outlined" style="color: #38a169;">
                        check_circle
                    </span>
                    <span style="font-weight: 500; color: #2d3748;">Cierre Consolidado</span>
                </div>
                <p style="color: #22543d; font-size: 14px; margin-bottom: 10px;">
                    Se realizará el cierre para todos los bancos activos del sistema.
                </p>
                <div id="resultado-verificacion-consolidado"></div>
            </div>
            
            <!-- Observaciones CORREGIDO COMPLETAMENTE -->
            <div style="margin-bottom: 24px;">
                <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                    <span class="material-symbols-outlined align-text-bottom text-base mr-1">
                        notes
                    </span>
                    Observaciones
                </label>
                <div class="relative">
                    <textarea name="observaciones" id="observaciones" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out @error('observaciones') border-red-500 ring-red-300 @enderror"
                              placeholder="Observaciones adicionales sobre el cierre..."></textarea>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400 text-lg">
                            description
                        </span>
                    </div>
                </div>
                @error('observaciones')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <span class="material-symbols-outlined text-sm mr-1">error</span>
                        {{ $message }}
                    </p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Opcional: Agregue observaciones sobre este cierre mensual.</p>
            </div>
            
            <!-- Resultado del cálculo -->
            <div id="resultado-calculo" style="display: none; margin-bottom: 24px;">
                <!-- Se llena dinámicamente -->
            </div>
            
            <!-- Botones de acción -->
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="button" onclick="calcularCierre()" class="secondary-btn">
                    <span class="material-symbols-outlined" style="font-size: 18px;">
                        calculate
                    </span>
                    Calcular Cierre
                </button>
                
                <button type="submit" id="btn-realizar-cierre" class="primary-btn" style="display: none;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">
                        lock
                    </span>
                    Realizar Cierre
                </button>
                
                <button type="button" onclick="generarConsolidadoPDF()" id="btn-generar-pdf" class="secondary-btn" style="display: none;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">
                        picture_as_pdf
                    </span>
                    Generar PDF
                </button>
            </div>
        </div>
    </form>
    
    <!-- Información de ayuda -->
    <div class="cierre-card" style="padding: 20px; background: #f8fafc;">
        <div style="display: flex; align-items: flex-start; gap: 12px;">
            <span class="material-symbols-outlined" style="color: #4299e1;">
                help
            </span>
            <div>
                <h3 style="font-weight: 600; color: #2d3748; margin-bottom: 8px;">
                    ¿Cómo funciona el cierre mensual?
                </h3>
                <ul style="color: #4a5568; font-size: 14px; line-height: 1.5; padding-left: 20px;">
                    <li>El cierre individual permite cerrar un mes específico para un banco en particular.</li>
                    <li>El cierre consolidado realiza el cierre para todos los bancos activos simultáneamente.</li>
                    <li>Una vez realizado el cierre, no se podrán modificar los movimientos de ese mes.</li>
                    <li>Puede generar un reporte PDF del consolidado antes o después del cierre.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
let tipoCierreActual = 'individual';

document.addEventListener('DOMContentLoaded', function() {
    seleccionarTipo('individual');
});

function seleccionarTipo(tipo) {
    tipoCierreActual = tipo;
    document.getElementById('tipoCierre').value = tipo;
    
    // Actualizar botones
    document.getElementById('btn-individual').classList.remove('active');
    document.getElementById('btn-consolidado').classList.remove('active');
    document.getElementById('btn-' + tipo).classList.add('active');
    
    // Mostrar/ocultar secciones
    if (tipo === 'individual') {
        document.getElementById('info-individual').style.display = 'block';
        document.getElementById('info-consolidado').style.display = 'none';
        document.getElementById('banco_id').required = true;
    } else {
        document.getElementById('info-individual').style.display = 'none';
        document.getElementById('info-consolidado').style.display = 'block';
        document.getElementById('banco_id').required = false;
    }
    
    // Limpiar resultados anteriores
    document.getElementById('resultado-calculo').style.display = 'none';
    document.getElementById('btn-realizar-cierre').style.display = 'none';
    document.getElementById('btn-generar-pdf').style.display = 'none';
}

function verificarCierre() {
    const mes = document.getElementById('mes').value;
    const bancoId = tipoCierreActual === 'individual' ? document.getElementById('banco_id').value : null;
    
    if (!mes) {
        Swal.fire({
            icon: 'warning',
            title: 'Datos incompletos',
            text: 'Por favor, seleccione un mes primero.',
            confirmButtonColor: '#3085d6',
        });
        return;
    }
    
    if (tipoCierreActual === 'individual' && !bancoId) {
        Swal.fire({
            icon: 'warning',
            title: 'Datos incompletos',
            text: 'Por favor, seleccione un banco.',
            confirmButtonColor: '#3085d6',
        });
        return;
    }
    
    const url = tipoCierreActual === 'individual' 
        ? `/api/verificar-cierre-existente?banco_id=${bancoId}&mes=${mes}`
        : `/api/verificar-cierre-existente?mes=${mes}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (tipoCierreActual === 'individual') {
                const resultadoDiv = document.getElementById('resultado-verificacion');
                if (data.existe) {
                    resultadoDiv.innerHTML = `
                        <div style="color: #e53e3e; font-size: 14px; display: flex; align-items: center; gap: 6px;">
                            <span class="material-symbols-outlined" style="font-size: 16px;">
                                warning
                            </span>
                            Ya existe un cierre para este banco y mes.
                        </div>
                    `;
                } else {
                    resultadoDiv.innerHTML = `
                        <div style="color: #38a169; font-size: 14px; display: flex; align-items: center; gap: 6px;">
                            <span class="material-symbols-outlined" style="font-size: 16px;">
                                check_circle
                            </span>
                            No existe cierre para este banco y mes. Puede proceder.
                        </div>
                    `;
                }
            } else {
                const resultadoDiv = document.getElementById('resultado-verificacion-consolidado');
                let html = '<div style="font-size: 14px;">';
                
                if (data.existe) {
                    html += '<p style="color: #e53e3e; margin-bottom: 8px;">Algunos bancos ya tienen cierre:</p>';
                    html += '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 8px;">';
                    
                    Object.values(data.detalles).forEach(item => {
                        html += `<div style="display: flex; align-items: center; gap: 4px;">
                            <span class="material-symbols-outlined" style="font-size: 14px; color: ${item.existe ? '#e53e3e' : '#38a169'}">
                                ${item.existe ? 'warning' : 'check_circle'}
                            </span>
                            <span>${item.nombre}</span>
                        </div>`;
                    });
                    
                    html += '</div>';
                } else {
                    html += '<p style="color: #38a169;">Ningún banco tiene cierre para este mes. Puede proceder con el cierre consolidado.</p>';
                }
                
                html += '</div>';
                resultadoDiv.innerHTML = html;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al verificar el cierre.',
                confirmButtonColor: '#d33',
            });
        });
}

function calcularCierre() {
    const mes = document.getElementById('mes').value;
    
    if (!mes) {
        Swal.fire({
            icon: 'warning',
            title: 'Datos incompletos',
            text: 'Por favor, seleccione un mes.',
            confirmButtonColor: '#3085d6',
        });
        return;
    }
    
    if (tipoCierreActual === 'individual') {
        const bancoId = document.getElementById('banco_id').value;
        if (!bancoId) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, seleccione un banco.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }
        
        fetch(`/api/calcular-cierre?banco_id=${bancoId}&mes=${mes}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarResultadoIndividual(data.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonColor: '#d33',
                    });
                }
            });
    } else {
        fetch(`/api/calcular-consolidado?mes=${mes}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarResultadoConsolidado(data.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonColor: '#d33',
                    });
                }
            });
    }
}

function mostrarResultadoIndividual(data) {
    const resultadoDiv = document.getElementById('resultado-calculo');
    
    resultadoDiv.innerHTML = `
        <div style="background: #f8fafc; border-radius: 8px; padding: 16px;">
            <h3 style="font-weight: 600; color: #2d3748; margin-bottom: 12px;">Resumen del Cierre</h3>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                <div>
                    <div style="font-size: 12px; color: #4a5568;">Banco</div>
                    <div style="font-weight: 500; color: #2d3748;">${data.banco_nombre}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #4a5568;">Mes</div>
                    <div style="font-weight: 500; color: #2d3748;">${data.mes_formateado}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #4a5568;">Total Ingresos</div>
                    <div style="font-weight: 600; color: #38a169;">$${parseFloat(data.total_ingresos).toFixed(2)}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #4a5568;">Total Egresos</div>
                    <div style="font-weight: 600; color: #e53e3e;">$${parseFloat(data.total_egresos).toFixed(2)}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #4a5568;">Cantidad Movimientos</div>
                    <div style="font-weight: 500; color: #2d3748;">${data.cantidad_movimientos}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #4a5568;">Saldo Final</div>
                    <div style="font-weight: 600; color: ${data.saldo_final >= 0 ? '#38a169' : '#e53e3e'}">
                        $${parseFloat(data.saldo_final).toFixed(2)}
                    </div>
                </div>
            </div>
            <div style="margin-top: 12px; font-size: 13px; color: #4a5568;">
                Último movimiento: ${data.ultimo_movimiento_fecha}
            </div>
        </div>
    `;
    
    resultadoDiv.style.display = 'block';
    document.getElementById('btn-realizar-cierre').style.display = 'inline-flex';
}

function mostrarResultadoConsolidado(data) {
    const resultadoDiv = document.getElementById('resultado-calculo');
    
    let bancosHTML = '';
    data.consolidado.forEach(item => {
        bancosHTML += `
            <div style="border-bottom: 1px solid #e2e8f0; padding: 8px 0;">
                <div style="display: flex; justify-content: space-between;">
                    <div style="font-weight: 500;">${item.banco.nombre}</div>
                    <div style="font-weight: 600; color: ${item.saldo_final >= 0 ? '#38a169' : '#e53e3e'}">
                        $${parseFloat(item.saldo_final).toFixed(2)}
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 12px; color: #4a5568;">
                    <span>Ingresos: $${parseFloat(item.total_ingresos).toFixed(2)}</span>
                    <span>Egresos: $${parseFloat(item.total_egresos).toFixed(2)}</span>
                    <span>Movimientos: ${item.cantidad_movimientos}</span>
                </div>
            </div>
        `;
    });
    
    resultadoDiv.innerHTML = `
        <div style="background: #f8fafc; border-radius: 8px; padding: 16px;">
            <h3 style="font-weight: 600; color: #2d3748; margin-bottom: 12px;">Resumen del Cierre Consolidado</h3>
            <div style="margin-bottom: 16px;">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 16px;">
                    <div style="text-align: center; padding: 12px; background: white; border-radius: 6px;">
                        <div style="font-size: 12px; color: #4a5568;">Total Ingresos</div>
                        <div style="font-weight: 600; color: #38a169; font-size: 18px;">
                            $${parseFloat(data.totales.total_ingresos).toFixed(2)}
                        </div>
                    </div>
                    <div style="text-align: center; padding: 12px; background: white; border-radius: 6px;">
                        <div style="font-size: 12px; color: #4a5568;">Total Egresos</div>
                        <div style="font-weight: 600; color: #e53e3e; font-size: 18px;">
                            $${parseFloat(data.totales.total_egresos).toFixed(2)}
                        </div>
                    </div>
                    <div style="text-align: center; padding: 12px; background: white; border-radius: 6px;">
                        <div style="font-size: 12px; color: #4a5568;">Total Movimientos</div>
                        <div style="font-weight: 600; color: #2d3748; font-size: 18px;">
                            ${data.totales.total_movimientos}
                        </div>
                    </div>
                    <div style="text-align: center; padding: 12px; background: white; border-radius: 6px;">
                        <div style="font-size: 12px; color: #4a5568;">Saldo Final Total</div>
                        <div style="font-weight: 600; color: ${data.totales.saldo_final >= 0 ? '#38a169' : '#e53e3e'}; font-size: 18px;">
                            $${parseFloat(data.totales.saldo_final).toFixed(2)}
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 16px;">
                    <div style="font-size: 13px; color: #4a5568; margin-bottom: 8px;">Detalle por banco:</div>
                    <div style="max-height: 200px; overflow-y: auto;">
                        ${bancosHTML}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    resultadoDiv.style.display = 'block';
    document.getElementById('btn-realizar-cierre').style.display = 'inline-flex';
    document.getElementById('btn-generar-pdf').style.display = 'inline-flex';
}

function generarConsolidadoPDF() {
    const mes = document.getElementById('mes').value;
    
    if (!mes) {
        Swal.fire({
            icon: 'warning',
            title: 'Datos incompletos',
            text: 'Por favor, seleccione un mes.',
            confirmButtonColor: '#3085d6',
        });
        return;
    }
    
    // Crear formulario temporal para enviar POST
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("cierres-mensuales.generar-consolidado-pdf") }}';
    form.target = '_blank';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    const mesInput = document.createElement('input');
    mesInput.type = 'hidden';
    mesInput.name = 'mes';
    mesInput.value = mes;
    form.appendChild(mesInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Validar formulario antes de enviar
document.getElementById('formCierre').addEventListener('submit', function(e) {
    const mes = document.getElementById('mes').value;
    
    if (!mes) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Datos incompletos',
            text: 'Por favor, seleccione un mes.',
            confirmButtonColor: '#3085d6',
        });
        return;
    }
    
    if (tipoCierreActual === 'individual') {
        const bancoId = document.getElementById('banco_id').value;
        if (!bancoId) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, seleccione un banco.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }
    }
    
    // Confirmación con SweetAlert2
    e.preventDefault();
    Swal.fire({
        title: '¿Está seguro de realizar el cierre?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, realizar cierre',
        cancelButtonText: 'Cancelar',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Si se confirma, entonces enviar el formulario
            this.submit();
        }
    });
});
</script>
@endsection