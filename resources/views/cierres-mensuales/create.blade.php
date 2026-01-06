@extends('layouts.app')

@section('title', 'Crear Cierre Mensual')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Crear Cierre Mensual</h2>
            <p class="text-gray-600">Registre el cierre contable de un mes</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('cierres-mensuales.store') }}" method="POST" id="cierreForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Banco -->
                    <div>
                        <label for="banco_id" class="block text-sm font-medium text-gray-700 mb-2">Banco *</label>
                        <select name="banco_id" id="banco_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('banco_id') border-red-500 @enderror">
                            <option value="">Seleccione un banco</option>
                            @foreach($bancos as $banco)
                            <option value="{{ $banco->id }}" {{ old('banco_id') == $banco->id ? 'selected' : '' }}
                                    data-saldo="{{ $banco->saldo_actual }}">
                                {{ $banco->nombre }} - Saldo: ${{ number_format($banco->saldo_actual, 2) }}
                            </option>
                            @endforeach
                        </select>
                        @error('banco_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="bancoSaldoInfo" class="mt-2 text-sm text-gray-500">
                            Saldo actual: <span id="saldoActual">$0.00</span>
                        </div>
                    </div>

                    <!-- Mes a cerrar -->
                    <div>
                        <label for="mes" class="block text-sm font-medium text-gray-700 mb-2">Mes a Cerrar *</label>
                        <input type="month" name="mes" id="mes" value="{{ old('mes', now()->format('Y-m')) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mes') border-red-500 @enderror">
                        @error('mes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Seleccione el mes que desea cerrar</p>
                    </div>

                    <!-- Información del cierre -->
                    <div class="md:col-span-2 p-4 bg-blue-50 rounded-lg">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Información del Cierre</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-blue-700">Último movimiento del mes:</span>
                                <span id="ultimoMovimiento" class="font-medium ml-2">-</span>
                            </div>
                            <div>
                                <span class="text-blue-700">Saldo final calculado:</span>
                                <span id="saldoFinalCalculado" class="font-bold text-green-600 ml-2">$0.00</span>
                            </div>
                        </div>
                        <button type="button" id="calcularCierre" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                            <span class="material-symbols-outlined align-text-bottom" style="font-size: 16px;">
                                refresh
                            </span>
                            Recalcular información
                        </button>
                    </div>

                    <!-- Observaciones -->
                    <div class="md:col-span-2">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('observaciones') }}</textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('cierres-mensuales.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                        <span class="material-symbols-outlined mr-2">
                            arrow_back
                        </span>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                        <span class="material-symbols-outlined mr-2">
                            lock
                        </span>
                        Cerrar Mes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bancoSelect = document.getElementById('banco_id');
    const mesInput = document.getElementById('mes');
    const saldoActualSpan = document.getElementById('saldoActual');
    const ultimoMovimientoSpan = document.getElementById('ultimoMovimiento');
    const saldoFinalSpan = document.getElementById('saldoFinalCalculado');
    const calcularBtn = document.getElementById('calcularCierre');
    const cierreForm = document.getElementById('cierreForm');

    // Mostrar saldo del banco seleccionado
    if (bancoSelect) {
        bancoSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const saldo = selectedOption.getAttribute('data-saldo');
            
            if (saldo) {
                saldoActualSpan.textContent = '$' + parseFloat(saldo).toFixed(2);
                if (bancoSelect.value && mesInput.value) {
                    calcularInformacionCierre();
                }
            } else {
                saldoActualSpan.textContent = '$0.00';
            }
        });
    }

    // Recalcular al cambiar el mes
    if (mesInput) {
        mesInput.addEventListener('change', function() {
            if (bancoSelect.value && mesInput.value) {
                calcularInformacionCierre();
            }
        });
    }

    // Botón para recalcular
    if (calcularBtn) {
        calcularBtn.addEventListener('click', function() {
            if (bancoSelect.value && mesInput.value) {
                calcularInformacionCierre();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Datos incompletos',
                    text: 'Por favor, seleccione un banco y un mes primero.',
                    confirmButtonColor: '#3085d6',
                });
            }
        });
    }

    // Función para calcular la información del cierre
    function calcularInformacionCierre() {
        const bancoId = bancoSelect.value;
        const mes = mesInput.value;
        
        // Mostrar loading
        ultimoMovimientoSpan.textContent = 'Calculando...';
        saldoFinalSpan.textContent = '$0.00';

        // Hacer petición AJAX
        fetch(`/api/calcular-cierre?banco_id=${bancoId}&mes=${mes}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    ultimoMovimientoSpan.textContent = data.ultimo_movimiento_fecha || 'No hay movimientos';
                    saldoFinalSpan.textContent = '$' + parseFloat(data.saldo_final).toFixed(2);
                    saldoFinalSpan.className = 'font-bold ' + (data.saldo_final >= 0 ? 'text-green-600' : 'text-red-600') + ' ml-2';
                } else {
                    ultimoMovimientoSpan.textContent = 'Error';
                    saldoFinalSpan.textContent = '$0.00';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonColor: '#d33',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                ultimoMovimientoSpan.textContent = 'Error';
                saldoFinalSpan.textContent = '$0.00';
            });
    }

    // Validar antes de enviar
    if (cierreForm) {
        cierreForm.addEventListener('submit', function(e) {
            const bancoId = bancoSelect.value;
            const mes = mesInput.value;
            
            if (!bancoId || !mes) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Datos incompletos',
                    text: 'Por favor, complete todos los campos requeridos.',
                    confirmButtonColor: '#3085d6',
                });
                return false;
            }

            // Verificar si ya existe un cierre para este mes
            fetch(`/api/verificar-cierre-existente?banco_id=${bancoId}&mes=${mes}`)
                .then(response => response.json())
                .then(data => {
                    if (data.existe) {
                        e.preventDefault();
                        Swal.fire({
                            title: '⚠️ Cierre existente',
                            html: `Ya existe un cierre para el mes de <strong>${data.mes}</strong> en este banco.<br><br>
                                  ¿Desea continuar de todos modos?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sí, crear nuevo',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                cierreForm.submit();
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    }

    // Inicializar si hay valores
    if (bancoSelect.value && mesInput.value) {
        calcularInformacionCierre();
    }
});
</script>
@endpush
@endsection