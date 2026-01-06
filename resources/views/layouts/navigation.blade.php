<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center mr-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        @if($empresaConfig->logo_url)
                            <img src="{{ $empresaConfig->logo_url }}" 
                                 alt="{{ $empresaConfig->nombre_empresa }}"
                                 class="h-10 w-auto mr-3 transition-transform duration-300 hover:scale-105">
                        @endif
                        <span class="font-bold text-xl text-gray-800 hover:text-gray-900 transition-colors">{{ $empresaConfig->nombre_empresa }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-6 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="nav-item px-4 py-2">
                        <span class="material-symbols-outlined text-sm">dashboard</span> Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('bancos.index')" :active="request()->routeIs('bancos.*')" class="nav-item px-4 py-2">
                        <span class="material-symbols-outlined text-sm">account_balance</span> Bancos
                    </x-nav-link>
                    <x-nav-link :href="route('movimientos.index')" :active="request()->routeIs('movimientos.*')" class="nav-item px-4 py-2">
                        <span class="material-symbols-outlined text-sm">swap_horiz</span> Movimientos
                    </x-nav-link>
                    <x-nav-link :href="route('cierres-mensuales.index')" :active="request()->routeIs('cierres-mensuales.*')" class="nav-item px-4 py-2">
                        <span class="material-symbols-outlined text-sm">calendar_month</span> Cierres
                    </x-nav-link>
                </div>
            </div>

            <!-- Profile Section - Diseño mejorado -->
            <div class="flex items-center sm:ms-6">
                <x-dropdown align="right" width="60"> <!-- Ancho aumentado para más opciones -->
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm focus:outline-none transition duration-150 ease-in-out">
                            <div class="hidden md:flex flex-col items-end mr-3">
                                <span class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 border-2 border-white shadow-md rounded-full w-10 h-10 flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ Auth::user()->email }}</p>
                        </div>
                        
                        <div class="py-1">
                            <x-dropdown-link :href="route('profile.edit')" class="dropdown-item flex items-center py-2.5">
                                <span class="material-symbols-outlined mr-3 text-blue-500">person</span>
                                <span class="flex-1">Mi Perfil</span>
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Editar</span>
                            </x-dropdown-link>
                            
                            <div class="border-t border-gray-200 my-1"></div>
                            
                            <div class="px-4 py-2 bg-gray-50">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Configuración</p>
                            </div>
                            
                            <x-dropdown-link :href="route('empresa-config.edit')" class="dropdown-item flex items-center py-2.5">
                                <span class="material-symbols-outlined mr-3 text-green-500">business</span>
                                <span class="flex-1">Empresa</span>
                            </x-dropdown-link>
                            
                            <x-dropdown-link :href="route('tipos-movimiento.index')" class="dropdown-item flex items-center py-2.5">
                                <span class="material-symbols-outlined mr-3 text-purple-500">category</span>
                                <span class="flex-1">Tipos de Movimiento</span>
                            </x-dropdown-link>
                            
                            <div class="border-t border-gray-200 my-1"></div>
                            
                            <div class="px-4 py-2 bg-gray-50">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sistema</p>
                            </div>
                            
                            <x-dropdown-link href="#" class="dropdown-item flex items-center py-2.5">
                                <span class="material-symbols-outlined mr-3 text-yellow-500">settings</span>
                                <span class="flex-1">Preferencias</span>
                            </x-dropdown-link>
                            
                            <x-dropdown-link href="#" class="dropdown-item flex items-center py-2.5">
                                <span class="material-symbols-outlined mr-3 text-indigo-500">help</span>
                                <span class="flex-1">Ayuda</span>
                            </x-dropdown-link>
                            
                            <div class="border-t border-gray-200 my-1"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="dropdown-item flex items-center py-2.5 text-red-600 hover:bg-red-50"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    <span class="material-symbols-outlined mr-3 text-red-500">logout</span>
                                    <span class="flex-1">Cerrar Sesión</span>
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-4 pb-4 space-y-2 bg-gray-50 border-b border-gray-200">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="responsive-item py-3">
                <span class="material-symbols-outlined mr-3">dashboard</span> Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('bancos.index')" :active="request()->routeIs('bancos.*')" class="responsive-item py-3">
                <span class="material-symbols-outlined mr-3">account_balance</span> Bancos
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('movimientos.index')" :active="request()->routeIs('movimientos.*')" class="responsive-item py-3">
                <span class="material-symbols-outlined mr-3">swap_horiz</span> Movimientos
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cierres-mensuales.index')" :active="request()->routeIs('cierres-mensuales.*')" class="responsive-item py-3">
                <span class="material-symbols-outlined mr-3">calendar_month</span> Cierres Mensuales
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Profile Section -->
        <div class="pt-5 pb-4 border-t border-gray-200">
            <div class="px-6 flex items-center">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 border-2 border-white shadow-md rounded-full w-12 h-12 flex items-center justify-center text-white font-bold text-lg">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="ml-4">
                    <div class="font-semibold text-lg text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-4 space-y-2 px-4">
                <x-responsive-nav-link :href="route('profile.edit')" class="responsive-item py-3">
                    <span class="material-symbols-outlined mr-3 text-blue-500">person</span> Mi Perfil
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('empresa-config.edit')" class="responsive-item py-3">
                    <span class="material-symbols-outlined mr-3 text-green-500">business</span> Configuración Empresa
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('tipos-movimiento.index')" class="responsive-item py-3">
                    <span class="material-symbols-outlined mr-3 text-purple-500">category</span> Tipos de Movimiento
                </x-responsive-nav-link>
                
                <div class="border-t border-gray-200 my-2"></div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" class="responsive-item py-3 text-red-600"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        <span class="material-symbols-outlined mr-3 text-red-500">logout</span> Cerrar Sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    .nav-item {
        @apply flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-all duration-200;
    }
    .nav-item.active {
        @apply bg-blue-50 text-blue-700 font-medium;
    }
    .dropdown-item {
        @apply flex items-center px-5 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200;
    }
    .responsive-item {
        @apply flex items-center px-5 py-2.5 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition-colors duration-200;
    }
    .responsive-item.active {
        @apply bg-blue-50 text-blue-700 font-medium;
    }
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        font-size: 1.15rem;
        line-height: 1;
    }
</style>