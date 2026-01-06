@extends('layouts.app')

@section('title', 'Editar Banco')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Editar Banco: {{ $banco->nombre }}</h2>
            <p class="text-gray-600">Modifique los datos del banco</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('bancos.update', $banco) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna izquierda -->
                    <div class="space-y-4">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Banco *</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $banco->nombre) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre') border-red-500 @enderror">
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="numero_cuenta" class="block text-sm font-medium text-gray-700 mb-2">Número de Cuenta *</label>
                            <input type="text" name="numero_cuenta" id="numero_cuenta" value="{{ old('numero_cuenta', $banco->numero_cuenta) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('numero_cuenta') border-red-500 @enderror">
                            @error('numero_cuenta')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nombre_propietario" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Propietario *</label>
                            <input type="text" name="nombre_propietario" id="nombre_propietario" value="{{ old('nombre_propietario', $banco->nombre_propietario) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre_propietario') border-red-500 @enderror">
                            @error('nombre_propietario')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="space-y-4">
                        <div>
                            <label for="saldo_inicial" class="block text-sm font-medium text-gray-700 mb-2">Saldo Inicial *</label>
                            @if($banco->movimientos()->count() > 0)
                                <!-- Solo lectura cuando hay movimientos -->
                                <input type="number" step="0.01" id="saldo_inicial_display" 
                                       value="{{ old('saldo_inicial', $banco->saldo_inicial) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                                       readonly disabled>
                                <input type="hidden" name="saldo_inicial" value="{{ old('saldo_inicial', $banco->saldo_inicial) }}">
                            @else
                                <!-- Editable cuando NO hay movimientos -->
                                <input type="number" step="0.01" name="saldo_inicial" id="saldo_inicial" 
                                       value="{{ old('saldo_inicial', $banco->saldo_inicial) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('saldo_inicial') border-red-500 @enderror">
                            @endif
                            
                            @error('saldo_inicial')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            @if($banco->movimientos()->count() > 0)
                                <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded">
                                    <p class="text-sm text-yellow-700 flex items-center">
                                        <span class="material-symbols-outlined mr-1 text-yellow-500" style="font-size: 16px;">
                                            lock
                                        </span>
                                        El saldo inicial no se puede modificar porque ya existen movimientos registrados.
                                    </p>
                                </div>
                            @else
                                <p class="mt-1 text-sm text-gray-500">Puede modificar el saldo inicial mientras no haya movimientos registrados.</p>
                            @endif
                        </div>

                        <div>
                            <label for="saldo_actual" class="block text-sm font-medium text-gray-700 mb-2">Saldo Actual</label>
                            <div class="relative">
                                <input type="text" id="saldo_actual" 
                                       value="${{ number_format($banco->saldo_actual, 2) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 font-bold {{ $banco->saldo_actual >= 0 ? 'text-green-600' : 'text-red-600' }}"
                                       readonly disabled>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Calculado automáticamente a partir de los movimientos.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <div class="flex items-center">
                                <input type="checkbox" name="activo" id="activo" value="1" 
                                       {{ old('activo', $banco->activo) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="activo" class="ml-2 text-sm text-gray-700">Activo</label>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción (ancho completo) -->
                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $banco->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Información adicional -->
                    <div class="md:col-span-2 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Información del Banco</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="p-3 bg-white rounded border">
                                <span class="text-gray-500 block mb-1">Total Movimientos:</span>
                                <span class="font-bold text-blue-600 text-lg">{{ $banco->movimientos()->count() }}</span>
                            </div>
                            <div class="p-3 bg-white rounded border">
                                <span class="text-gray-500 block mb-1">Último Movimiento:</span>
                                <span class="font-medium">
                                    @if($banco->movimientos()->count() > 0)
                                        {{ $banco->movimientos()->latest()->first()->fecha->format('d/m/Y') }}
                                    @else
                                        <span class="text-gray-400">Ninguno</span>
                                    @endif
                                </span>
                            </div>
                            <div class="p-3 bg-white rounded border">
                                <span class="text-gray-500 block mb-1">Fecha de Creación:</span>
                                <span class="font-medium">{{ $banco->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        
                        @if($banco->movimientos()->count() > 0)
                        <div class="mt-3 text-xs text-gray-500">
                            <span class="material-symbols-outlined align-middle mr-1" style="font-size: 14px;">
                                info
                            </span>
                            El banco tiene {{ $banco->movimientos()->count() }} movimiento(s) registrado(s). 
                            Para modificar el saldo inicial, primero debe eliminar todos los movimientos.
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('bancos.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                        <span class="material-symbols-outlined mr-2">
                            arrow_back
                        </span>
                        Volver
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                        <span class="material-symbols-outlined mr-2">
                            save
                        </span>
                        Actualizar Banco
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection