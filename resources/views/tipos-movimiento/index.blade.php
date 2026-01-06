@extends('layouts.app')

@section('title', 'Tipos de Movimiento')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="material-symbols-outlined mr-2">
                    category
                </span>
                Tipos de Movimiento
            </h2>
            <a href="{{ route('tipos-movimiento.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <span class="material-symbols-outlined mr-2">add</span> 
                Nuevo Tipo
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
            {{ session('error') }}
        </div>
        @endif

        <!-- Tabla de Tipos de Movimiento -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                    tag
                                </span>
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                    title
                                </span>
                                Nombre
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px; color: #38a169;">
                                    trending_up
                                </span>
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px; color: #e53e3e; margin-left: 4px;">
                                    trending_down
                                </span>
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                    description
                                </span>
                                Descripción
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                    toggle_on
                                </span>
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                    settings
                                </span>
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tipos as $tipo)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px; color: #718096;">
                                    tag
                                </span>
                                {{ $tipo->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined mr-2" style="vertical-align: middle; font-size: 18px; color: #4a5568;">
                                        category
                                    </span>
                                    {{ $tipo->nombre }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                      {{ $tipo->tipo == 'ingreso' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 14px;">
                                        {{ $tipo->tipo == 'ingreso' ? 'trending_up' : 'trending_down' }}
                                    </span>
                                    {{ ucfirst($tipo->tipo) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $tipo->descripcion ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                      {{ $tipo->activo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 14px;">
                                        {{ $tipo->activo ? 'check_circle' : 'cancel' }}
                                    </span>
                                    {{ $tipo->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('tipos-movimiento.edit', $tipo->id) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 p-2 rounded-lg hover:bg-yellow-50 transition-colors duration-200"
                                       title="Editar">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                    <form action="{{ route('tipos-movimiento.destroy', $tipo->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200 delete-tipo-btn"
                                                title="Eliminar"
                                                data-nombre="{{ $tipo->nombre }}">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <div class="py-8 flex flex-col items-center">
                                    <span class="material-symbols-outlined text-gray-400 text-4xl mb-2">
                                        category
                                    </span>
                                    No hay tipos de movimiento registrados
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar eliminación con SweetAlert2
    const deleteButtons = document.querySelectorAll('.delete-tipo-btn');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const tipoNombre = this.getAttribute('data-nombre');
            const form = this.closest('form');
            
            Swal.fire({
                title: '¿Estás seguro?',
                html: `Vas a eliminar el tipo de movimiento: <strong>${tipoNombre}</strong><br><br>
                      <span class="text-red-600 text-sm">⚠️ Advertencia: Esta acción no se puede deshacer</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
@endsection