@extends('layouts.app')

@section('title', 'Configuración de Empresa')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="material-symbols-outlined mr-2">
                    business
                </span>
                Configuración de Empresa
            </h2>
            <p class="text-gray-600 flex items-center">
                <span class="material-symbols-outlined mr-1 text-sm">
                    settings
                </span>
                Configure los datos y logo de su empresa
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('empresa-config.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna 1: Información de la Empresa -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <span class="material-symbols-outlined mr-2">
                                info
                            </span>
                            Información de la Empresa
                        </h3>
                        
                        <div>
                            <label for="nombre_empresa" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de la Empresa *
                            </label>
                            <input type="text" name="nombre_empresa" id="nombre_empresa" 
                                   value="{{ old('nombre_empresa', $config->nombre_empresa) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre_empresa') border-red-500 @enderror"
                                   required>
                            @error('nombre_empresa')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                                Dirección
                            </label>
                            <textarea name="direccion" id="direccion" rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('direccion', $config->direccion) }}</textarea>
                        </div>

                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono
                            </label>
                            <input type="text" name="telefono" id="telefono" 
                                   value="{{ old('telefono', $config->telefono) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', $config->email) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                                Sitio Web
                            </label>
                            <input type="url" name="website" id="website" 
                                   value="{{ old('website', $config->website) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="https://ejemplo.com">
                        </div>

                        <div>
                            <label for="rnc" class="block text-sm font-medium text-gray-700 mb-2">
                                RNC / NIT
                            </label>
                            <input type="text" name="rnc" id="rnc" 
                                   value="{{ old('rnc', $config->rnc) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="footer_text" class="block text-sm font-medium text-gray-700 mb-2">
                                Texto del Pie de Página
                            </label>
                            <textarea name="footer_text" id="footer_text" rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('footer_text', $config->footer_text) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Este texto aparecerá en los reportes PDF</p>
                        </div>
                    </div>

                    <!-- Columna 2: Logo de la Empresa -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <span class="material-symbols-outlined mr-2">
                                image
                            </span>
                            Logo de la Empresa
                        </h3>

                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <!-- Vista previa del logo -->
                            <div id="logoPreview" class="mb-4">
                                @if($config->logo_url)
                                    <img src="{{ $config->logo_url }}" alt="Logo actual" 
                                         class="mx-auto max-h-48 max-w-full object-contain">
                                    <p class="mt-2 text-sm text-gray-600">Logo actual</p>
                                @else
                                    <div class="text-gray-400 flex flex-col items-center">
                                        <span class="material-symbols-outlined text-6xl mb-2">
                                            image
                                        </span>
                                        <p>No hay logo cargado</p>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4">
                                <label for="logo" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center transition-colors duration-200">
                                    <span class="material-symbols-outlined mr-2">
                                        upload
                                    </span>
                                    {{ $config->logo_url ? 'Cambiar Logo' : 'Subir Logo' }}
                                </label>
                                <input type="file" name="logo" id="logo" 
                                       class="hidden" 
                                       accept="image/*"
                                       onchange="previewLogo(event)">
                                
                                @error('logo')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                <p class="mt-2 text-xs text-gray-500">
                                    Formatos permitidos: JPEG, PNG, JPG, GIF, SVG<br>
                                    Tamaño máximo: 2MB
                                </p>

                                @if($config->logo_url)
                                    <div class="mt-4">
                                        <button type="button" 
                                                onclick="confirmDeleteLogo()"
                                                class="text-red-600 hover:text-red-800 text-sm flex items-center">
                                            <span class="material-symbols-outlined mr-1 text-sm">
                                                delete
                                            </span>
                                            Eliminar logo actual
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-blue-800 flex items-center mb-2">
                                <span class="material-symbols-outlined mr-1">
                                    lightbulb
                                </span>
                                Recomendaciones
                            </h4>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li class="flex items-start">
                                    <span class="material-symbols-outlined mr-1 text-xs">check</span>
                                    <span>El logo se mostrará en la navegación y reportes PDF</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-symbols-outlined mr-1 text-xs">check</span>
                                    <span>Usar logo en formato PNG con fondo transparente</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-symbols-outlined mr-1 text-xs">check</span>
                                    <span>Dimensiones recomendadas: 200x60 píxeles</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-4">
                    <a href="{{ route('dashboard') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                        <span class="material-symbols-outlined mr-2">
                            arrow_back
                        </span>
                        Volver al Dashboard
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                        <span class="material-symbols-outlined mr-2">
                            save
                        </span>
                        Guardar Configuración
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Formulario oculto para eliminar logo -->
<form id="deleteLogoForm" action="{{ route('empresa-config.delete-logo') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    // Vista previa del logo
    function previewLogo(event) {
        const input = event.target;
        const preview = document.getElementById('logoPreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Vista previa" 
                         class="mx-auto max-h-48 max-w-full object-contain">
                    <p class="mt-2 text-sm text-gray-600">Vista previa del nuevo logo</p>
                `;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Confirmar eliminación del logo
    function confirmDeleteLogo() {
        Swal.fire({
            title: '¿Eliminar logo?',
            text: 'El logo actual será eliminado permanentemente',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteLogoForm').submit();
            }
        });
    }
</script>
@endpush
@endsection