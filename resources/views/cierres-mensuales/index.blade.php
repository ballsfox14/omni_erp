@extends('layouts.app')

@section('title', 'Cierres Mensuales')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <span class="material-symbols-outlined mr-2">
                        event_available
                    </span>
                    Cierres Mensuales
                </h2>
                <p class="text-gray-600">Gestión de cierres contables mensuales</p>
            </div>
            <a href="{{ route('cierres-mensuales.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-colors duration-200">
                <span class="material-symbols-outlined mr-2">
                    add
                </span>
                Nuevo Cierre
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <span class="material-symbols-outlined text-green-600 mr-2">
                    check_circle
                </span>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if($cierres->isEmpty())
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <span class="material-symbols-outlined text-gray-400 text-6xl mb-4">
                    event_available
                </span>
                <p class="text-gray-500 text-lg mb-2">No hay cierres mensuales registrados</p>
                <p class="text-gray-400 text-sm mb-4">Comience cerrando un mes para continuar</p>
                <a href="{{ route('cierres-mensuales.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Crear primer cierre
                </a>
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Banco
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mes Cerrado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Saldo Final
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cerrado Por
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha Cierre
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cierres as $cierre)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $cierre->banco->nombre }}</div>
                                    <div class="text-sm text-gray-500">{{ $cierre->banco->numero_cuenta }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $cierre->fecha_cierre->translatedFormat('F Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold {{ $cierre->saldo_final >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        ${{ number_format($cierre->saldo_final, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $cierre->usuario ? $cierre->usuario->name : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $cierre->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('cierres-mensuales.show', $cierre) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200"
                                           title="Ver detalles">
                                            <span class="material-symbols-outlined">
                                                visibility
                                            </span>
                                        </a>
                                        <form action="{{ route('cierres-mensuales.destroy', $cierre) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200 delete-cierre-btn"
                                                    title="Eliminar"
                                                    data-mes="{{ $cierre->fecha_cierre->translatedFormat('F Y') }}"
                                                    data-banco="{{ $cierre->banco->nombre }}">
                                                <span class="material-symbols-outlined">
                                                    delete
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-cierre-btn');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const mes = this.getAttribute('data-mes');
            const banco = this.getAttribute('data-banco');
            const form = this.closest('form');
            
            Swal.fire({
                title: '¿Estás seguro?',
                html: `Vas a eliminar el cierre de <strong>${mes}</strong> del banco <strong>${banco}</strong><br><br>
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