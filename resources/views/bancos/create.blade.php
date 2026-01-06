@extends('layouts.app')

@section('title', 'Crear Banco')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Crear Nuevo Banco</h2>
            <p class="text-gray-600">Complete los datos para registrar un nuevo banco</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('bancos.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Banco *</label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre') border-red-500 @enderror">
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="numero_cuenta" class="block text-sm font-medium text-gray-700 mb-2">Número de Cuenta *</label>
                        <input type="text" name="numero_cuenta" id="numero_cuenta" value="{{ old('numero_cuenta') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('numero_cuenta') border-red-500 @enderror"
                               placeholder="Ej: 1234567890">
                        @error('numero_cuenta')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nombre_propietario" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Propietario *</label>
                        <input type="text" name="nombre_propietario" id="nombre_propietario" value="{{ old('nombre_propietario') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre_propietario') border-red-500 @enderror"
                               placeholder="Ej: Juan Pérez">
                        @error('nombre_propietario')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="saldo_inicial" class="block text-sm font-medium text-gray-700 mb-2">Saldo Inicial *</label>
                        <input type="number" step="0.01" name="saldo_inicial" id="saldo_inicial" value="{{ old('saldo_inicial', 0) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('saldo_inicial') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('saldo_inicial')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Este campo no podrá modificarse después si ya existen movimientos.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <div class="flex items-center">
                            <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="activo" class="ml-2 text-sm text-gray-700">Activo</label>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descripcion') border-red-500 @enderror"
                                  placeholder="Información adicional sobre la cuenta">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                        Guardar Banco
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection