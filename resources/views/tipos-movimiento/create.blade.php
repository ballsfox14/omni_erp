@extends('layouts.app')

@section('title', 'Crear Tipo de Movimiento')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="material-symbols-outlined mr-2">
                    add_circle
                </span>
                Crear Nuevo Tipo de Movimiento
            </h2>
            <p class="text-gray-600 flex items-center">
                <span class="material-symbols-outlined mr-1 text-sm">
                    info
                </span>
                Complete los datos para registrar un nuevo tipo de movimiento
            </p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('tipos-movimiento.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <span class="material-symbols-outlined mr-1 text-sm">
                                title
                            </span>
                            Nombre *
                        </label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre') border-red-500 @enderror"
                               placeholder="Ej: Remesa, Pago a proveedor"
                               autofocus>
                        @error('nombre')
                            <div class="mt-2 flex items-center text-sm text-red-600">
                                <span class="material-symbols-outlined mr-1 text-sm">
                                    error
                                </span>
                                {{ $message }}
                            </div>
                        @enderror
                        @if(!$errors->has('nombre'))
                            <p class="mt-1 text-xs text-gray-500">El nombre debe ser único en el sistema</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <span class="material-symbols-outlined mr-1 text-sm">
                            category
                            </span>
                            Tipo *
                        </label>
                        <select name="tipo" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tipo') border-red-500 @enderror">
                            <option value="">Seleccionar tipo</option>
                            <option value="ingreso" {{ old('tipo') == 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                            <option value="egreso" {{ old('tipo') == 'egreso' ? 'selected' : '' }}>Egreso</option>
                        </select>
                        @error('tipo')
                            <div class="mt-2 flex items-center text-sm text-red-600">
                                <span class="material-symbols-outlined mr-1 text-sm">
                                    error
                                </span>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <span class="material-symbols-outlined mr-1 text-sm">
                                description
                            </span>
                            Descripción
                        </label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descripcion') border-red-500 @enderror"
                                  placeholder="Descripción del tipo de movimiento">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="mt-2 flex items-center text-sm text-red-600">
                                <span class="material-symbols-outlined mr-1 text-sm">
                                    error
                                </span>
                                {{ $message }}
                            </div>
                        @enderror
                        @if(!$errors->has('descripcion'))
                            <p class="mt-1 text-xs text-gray-500">Máximo 255 caracteres</p>
                        @endif
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('tipos-movimiento.index') }}" 
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
                        Guardar Tipo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection