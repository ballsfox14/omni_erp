@extends('layouts.app')

@section('title', 'Editar Movimiento')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Editar Movimiento Bancario</h2>
            <p class="text-gray-600">Modifique los datos del movimiento</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('movimientos.update', $movimiento) }}" method="POST" id="movimientoForm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Banco -->
                    <div>
                        <label for="banco_id" class="block text-sm font-medium text-gray-700 mb-2">Banco *</label>
                        <select name="banco_id" id="banco_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('banco_id') border-red-500 @enderror">
                            <option value="">Seleccione un banco</option>
                            @foreach($bancos as $banco)
                            <option value="{{ $banco->id }}" {{ old('banco_id', $movimiento->banco_id) == $banco->id ? 'selected' : '' }}
                                    data-saldo="{{ $banco->saldo_actual }}">
                                {{ $banco->nombre }} - Saldo: ${{ number_format($banco->saldo_actual, 2) }}
                            </option>
                            @endforeach
                        </select>
                        @error('banco_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="bancoSaldoInfo" class="mt-2 text-sm text-gray-500 hidden">
                            Saldo actual: <span id="saldoActual"></span>
                        </div>
                    </div>

                    <!-- Fecha - MODIFICADO: con opción de fecha actual -->
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">Fecha del Movimiento *</label>
                        <div class="flex space-x-2">
                            <input type="date" name="fecha" id="fecha" value="{{ old('fecha', $movimiento->fecha->format('Y-m-d')) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fecha') border-red-500 @enderror">
                            <button type="button" id="usarFechaActual" 
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm whitespace-nowrap transition-colors duration-200">
                                Usar fecha actual
                            </button>
                        </div>
                        @error('fecha')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Movimiento -->
                    <div>
                        <label for="tipo_movimiento_id" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Movimiento *</label>
                        <select name="tipo_movimiento_id" id="tipo_movimiento_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tipo_movimiento_id') border-red-500 @enderror">
                            <option value="">Seleccione un tipo</option>
                            @foreach($tiposMovimiento as $tipo)
                            <option value="{{ $tipo->id }}" {{ old('tipo_movimiento_id', $movimiento->tipo_movimiento_id) == $tipo->id ? 'selected' : '' }}
                                    data-tipo="{{ $tipo->tipo }}">
                                {{ $tipo->nombre }} ({{ ucfirst($tipo->tipo) }})
                            </option>
                            @endforeach
                        </select>
                        @error('tipo_movimiento_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Concepto -->
                    <div>
                        <label for="concepto" class="block text-sm font-medium text-gray-700 mb-2">Concepto *</label>
                        <input type="text" name="concepto" id="concepto" value="{{ old('concepto', $movimiento->concepto) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('concepto') border-red-500 @enderror">
                        @error('concepto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Montos -->
                    @php
                        $esIngreso = $movimiento->tipoMovimiento->tipo == 'ingreso';
                    @endphp
                    
                    <div id="montoDebeDiv" class="{{ $esIngreso ? '' : 'hidden' }}">
                        <label for="monto_debe" class="block text-sm font-medium text-gray-700 mb-2">Monto Debe (Ingreso) *</label>
                        <input type="number" step="0.01" name="monto_debe" id="monto_debe" 
                               value="{{ old('monto_debe', $movimiento->monto_debe) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('monto_debe') border-red-500 @enderror"
                               {{ $esIngreso ? 'required' : '' }}>
                        @error('monto_debe')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="montoHaberDiv" class="{{ !$esIngreso ? '' : 'hidden' }}">
                        <label for="monto_haber" class="block text-sm font-medium text-gray-700 mb-2">Monto Haber (Egreso) *</label>
                        <input type="number" step="0.01" name="monto_haber" id="monto_haber" 
                               value="{{ old('monto_haber', $movimiento->monto_haber) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('monto_haber') border-red-500 @enderror"
                               {{ !$esIngreso ? 'required' : '' }}>
                        @error('monto_haber')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Referencia -->
                    <div>
                        <label for="referencia" class="block text-sm font-medium text-gray-700 mb-2">Referencia</label>
                        <input type="text" name="referencia" id="referencia" value="{{ old('referencia', $movimiento->referencia) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Observaciones CORREGIDO COMPLETAMENTE -->
                    <div class="md:col-span-2">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                        <div class="relative">
                            <textarea name="observaciones" id="observaciones" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out @error('observaciones') border-red-500 ring-red-300 @enderror"
                                      placeholder="Observaciones adicionales del movimiento...">{{ old('observaciones', $movimiento->observaciones) }}</textarea>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="material-symbols-outlined text-gray-400 text-lg">
                                    notes
                                </span>
                            </div>
                        </div>
                        @error('observaciones')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <span class="material-symbols-outlined text-sm mr-1">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Opcional: Agregue cualquier observación relevante sobre este movimiento.</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('movimientos.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                        <span class="material-symbols-outlined mr-2">
                            arrow_back
                        </span>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                        <span class="material-symbols-outlined mr-2">
                            save
                        </span>
                        Actualizar Movimiento
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
    const tipoSelect = document.getElementById('tipo_movimiento_id');
    const montoDebeDiv = document.getElementById('montoDebeDiv');
    const montoHaberDiv = document.getElementById('montoHaberDiv');
    const montoDebeInput = document.getElementById('monto_debe');
    const montoHaberInput = document.getElementById('monto_haber');
    const bancoSaldoInfo = document.getElementById('bancoSaldoInfo');
    const saldoActualSpan = document.getElementById('saldoActual');
    const fechaInput = document.getElementById('fecha');
    const usarFechaActualBtn = document.getElementById('usarFechaActual');

    // Botón para usar fecha actual
    if (usarFechaActualBtn && fechaInput) {
        usarFechaActualBtn.addEventListener('click', function() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            fechaInput.value = `${year}-${month}-${day}`;
        });
    }

    // Mostrar saldo del banco seleccionado
    if (bancoSelect) {
        bancoSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const saldo = selectedOption.getAttribute('data-saldo');
            
            if (saldo) {
                bancoSaldoInfo.classList.remove('hidden');
                saldoActualSpan.textContent = '$' + parseFloat(saldo).toFixed(2);
            } else {
                bancoSaldoInfo.classList.add('hidden');
            }
        });
        
        // Inicializar si ya hay un banco seleccionado
        if (bancoSelect.value) {
            bancoSelect.dispatchEvent(new Event('change'));
        }
    }

    // Cambiar entre monto debe y haber según tipo
    if (tipoSelect) {
        tipoSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const tipo = selectedOption.getAttribute('data-tipo');
            
            if (tipo === 'ingreso') {
                montoDebeDiv.classList.remove('hidden');
                montoHaberDiv.classList.add('hidden');
                montoDebeInput.required = true;
                montoHaberInput.required = false;
                montoHaberInput.value = 0;
            } else if (tipo === 'egreso') {
                montoDebeDiv.classList.add('hidden');
                montoHaberDiv.classList.remove('hidden');
                montoDebeInput.required = false;
                montoHaberInput.required = true;
                montoDebeInput.value = 0;
            } else {
                montoDebeDiv.classList.add('hidden');
                montoHaberDiv.classList.add('hidden');
                montoDebeInput.required = false;
                montoHaberInput.required = false;
            }
        });
        
        // Inicializar según el tipo actual
        if (tipoSelect.value) {
            tipoSelect.dispatchEvent(new Event('change'));
        }
    }

    // Validación de montos
    const movimientoForm = document.getElementById('movimientoForm');
    if (movimientoForm) {
        movimientoForm.addEventListener('submit', function(e) {
            const tipoOption = tipoSelect.options[tipoSelect.selectedIndex];
            const tipo = tipoOption.getAttribute('data-tipo');
            const montoDebe = parseFloat(montoDebeInput.value) || 0;
            const montoHaber = parseFloat(montoHaberInput.value) || 0;
            
            if (tipo === 'ingreso' && montoDebe <= 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Monto inválido',
                    text: 'Para un ingreso, el monto debe debe ser mayor a cero.',
                    confirmButtonColor: '#3085d6',
                });
                montoDebeInput.focus();
                return false;
            }
            
            if (tipo === 'egreso' && montoHaber <= 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Monto inválido',
                    text: 'Para un egreso, el monto haber debe ser mayor a cero.',
                    confirmButtonColor: '#3085d6',
                });
                montoHaberInput.focus();
                return false;
            }
        });
    }
});
</script>
@endpush
@endsection