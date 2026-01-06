@extends('layouts.app')

@section('title', 'Bancos')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <span class="material-symbols-outlined mr-2">
                        account_balance
                    </span>
                    Bancos
                </h2>
                <p class="text-gray-600">Gestión de cuentas bancarias</p>
            </div>
            <a href="{{ route('bancos.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-colors duration-200">
                <span class="material-symbols-outlined mr-2">
                    add
                </span>
                Nuevo Banco
            </a>
        </div>

        @if($bancos->isEmpty())
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <span class="material-symbols-outlined text-gray-400 text-6xl mb-4">
                    account_balance
                </span>
                <p class="text-gray-500 text-lg mb-2">No hay bancos registrados</p>
                <a href="{{ route('bancos.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Crear primer banco
                </a>
            </div>
        @else
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
                                        account_balance
                                    </span>
                                    Banco
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                        badge
                                    </span>
                                    Número de Cuenta
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px;">
                                        person
                                    </span>
                                    Propietario
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <span class="material-symbols-outlined mr-1" style="vertical-align: middle; font-size: 16px; color: #38a169;">
                                        account_balance_wallet
                                    </span>
                                    Saldo Actual
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
                            @foreach($bancos as $banco)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">#{{ $banco->id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <span class="material-symbols-outlined text-blue-600">
                                                account_balance
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $banco->nombre }}</div>
                                            @if($banco->descripcion)
                                            <div class="text-sm text-gray-500">{{ Str::limit($banco->descripcion, 30) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-mono">{{ $banco->numero_cuenta }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $banco->nombre_propietario }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                   <div class="text-sm font-bold {{ $banco->saldo_actual >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 16px; margin-right: 4px; {{ $banco->saldo_actual >= 0 ? 'color: #38a169;' : 'color: #e53e3e;' }}">
                                            {{ $banco->saldo_actual >= 0 ? 'trending_up' : 'trending_down' }}
                                        </span>
                                        ${{ number_format($banco->saldo_actual, 2) }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Inicial: ${{ number_format($banco->saldo_inicial, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $banco->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 14px; margin-right: 4px;">
                                            {{ $banco->activo ? 'check_circle' : 'cancel' }}
                                        </span>
                                        {{ $banco->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('bancos.show', $banco) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200"
                                           title="Ver detalles">
                                            <span class="material-symbols-outlined">
                                                visibility
                                            </span>
                                        </a>
                                        <a href="{{ route('bancos.edit', $banco) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 p-2 rounded-lg hover:bg-yellow-50 transition-colors duration-200"
                                           title="Editar">
                                            <span class="material-symbols-outlined">
                                                edit
                                            </span>
                                        </a>
                                        <button type="button" 
                                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200 delete-banco-btn"
                                                title="Eliminar"
                                                data-id="{{ $banco->id }}"
                                                data-nombre="{{ $banco->nombre }}">
                                            <span class="material-symbols-outlined">
                                                delete
                                            </span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Agregar paginación -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $bancos->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar eliminación con SweetAlert2
    const deleteButtons = document.querySelectorAll('.delete-banco-btn');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const bancoId = this.getAttribute('data-id');
            const bancoNombre = this.getAttribute('data-nombre');
            
            Swal.fire({
                title: '¿Estás seguro?',
                html: `Vas a eliminar el banco: <strong>${bancoNombre}</strong><br><br>
                      <span class="text-red-600 text-sm">⚠️ Advertencia: Esta acción no se puede deshacer</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                backdrop: true,
                allowOutsideClick: false,
                allowEscapeKey: true,
                allowEnterKey: false,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    // Crear un formulario de eliminación dinámico
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/bancos/${bancoId}`;
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    
                    return form.submit();
                }
            });
        });
    });
});
</script>
@endpush
@endsection