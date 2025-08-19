<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'URUS · Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="color-scheme" content="light">
    <style>
        /* Scrollbar sutil */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 9999px
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af
        }
    </style>
</head>

<body class="h-full bg-gray-100 text-gray-800">
    {{-- Mobile menu toggle (CSS-only) --}}
    <input id="sidebar-toggle" type="checkbox" class="hidden peer" />

    <div class="min-h-full">
        {{-- Topbar --}}
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <label for="sidebar-toggle"
                        class="md:hidden inline-flex items-center p-2 rounded hover:bg-gray-100 cursor-pointer"
                        title="Menú">
                        {{-- icon menu --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </label>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-semibold tracking-tight">
                        <span
                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gray-900 text-white">U</span>
                        <span>URUS · Panel</span>
                    </a>
                </div>

                @auth
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex flex-col leading-tight">
                            <span
                                class="text-sm font-medium">{{ auth()->user()->nombre_completo ?? (auth()->user()->username ?? 'Usuario') }}</span>
                            <span class="text-xs text-gray-500">{{ auth()->user()->email ?? 'Sin email' }}</span>
                        </div>
                        <div
                            class="h-9 w-9 rounded-full bg-gray-200 flex items-center justify-center text-sm font-semibold">
                            {{ strtoupper(substr(auth()->user()->username ?? 'U', 0, 1)) }}
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="ml-1">
                            @csrf
                            <button
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-900 text-white hover:bg-black text-sm">
                                {{-- icon logout --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M18 12H9m9 0l-3-3m3 3l-3 3" />
                                </svg>
                                Salir
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </header>

        <div class="max-w-7xl mx-auto">
            <div class="flex">
                {{-- Sidebar --}}
                @auth
                    @if (auth()->user()->rol === 'superadministrador')
                        <aside
                            class="fixed inset-y-0 left-0 z-20 w-72 -translate-x-full peer-checked:translate-x-0 md:translate-x-0 md:static md:z-auto
                        bg-white border-r md:rounded-none rounded-r-2xl shadow md:shadow-none transition-transform">
                            <div class="h-14 md:hidden"></div>
                            <nav class="p-4 space-y-1">
                                <div class="px-2 pt-2 pb-1 text-xs font-semibold uppercase text-gray-500">Navegación</div>
                                @auth
                                    @if (auth()->user()->rol === 'superadministrador')
                                        <a href="{{ route('dashboard') }}"
                                            class="group flex items-center gap-3 px-3 py-2 rounded-lg
                                                    {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80 group-hover:opacity-100"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h4m6 0h4a1 1 0 001-1V10" />
                                            </svg>
                                            <div class="flex flex-col leading-tight">
                                                <span class="text-sm font-medium">Dashboard</span>
                                                <span class="text-xs opacity-70">Inicio</span>
                                            </div>
                                        </a>
                                        <a href="{{ route('usuarios.index') }}"
                                            class="group flex items-center gap-3 px-3 py-2 rounded-lg
                                                    {{ request()->routeIs('usuarios.*') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 opacity-80 group-hover:opacity-100" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor">
                                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16 14a4 4 0 10-8 0v3h8v-3z" />
                                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 7a3 3 0 110-6 3 3 0 010 6z" />
                                            </svg>
                                            <div class="flex flex-col leading-tight">
                                                <span class="text-sm font-medium">Usuarios</span>
                                                <span class="text-xs opacity-70">Gestión completa</span>
                                            </div>
                                        </a>
                                        {{-- <a href="{{ route('resumen.index') }}"
                                            class="group flex items-center gap-3 px-3 py-2 rounded-lg
                                            {{ request()->routeIs('resumen.*') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 opacity-80 group-hover:opacity-100" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor">
                                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 12h18M3 6h18M3 18h18" />
                                            </svg>
                                            <div class="flex flex-col leading-tight">
                                                <span class="text-sm font-medium">Resumen</span>
                                                <span class="text-xs opacity-70">Diario</span>
                                            </div>
                                        </a> --}}
                                        <a href="{{ route('participantes.index') }}"
                                            class="group flex items-center gap-3 px-3 py-2 rounded-lg
                                            {{ request()->routeIs('participantes.*') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 opacity-80 group-hover:opacity-100" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor">
                                                <rect x="3" y="4" width="18" height="16" rx="2" ry="2"
                                                    stroke-width="1.8"></rect>
                                                <path d="M7 8h7M7 12h10M7 16h6" stroke-width="1.8" stroke-linecap="round"></path>
                                            </svg>
                                            <div class="flex flex-col leading-tight">
                                                <span class="text-sm font-medium">Participantes</span>
                                                <span class="text-xs opacity-70">Registro</span>
                                            </div>
                                        </a>
                                    @endif
                                @endauth

                                {{-- (Placeholder) Módulos futuros --}}
                                <div class="mt-4 px-3 py-2 rounded-lg border border-dashed text-xs text-gray-500">
                                    Próximamente: Reportes, Provincias/Municipios, Circunscripciones…
                                </div>
                            </nav>

                            {{-- Footer sidebar --}}
                            <div class="mt-auto p-4 border-t">
                                <div class="text-xs text-gray-500">
                                    © {{ date('Y') }} URUS — Panel
                                </div>
                            </div>
                        </aside>
                    @endif
                @endauth
                {{-- Content --}}
                <main class="flex-1 w-full md:ml-0 ml-0 md:pl-6 p-4 md:p-8">
                    {{-- breadcrumb / título --}}
                    <div class="mb-6">
                        <h1 class="text-2xl font-semibold tracking-tight">@yield('title', 'Panel')</h1>
                        @hasSection('subtitle')
                            <p class="text-sm text-gray-500 mt-1">@yield('subtitle')</p>
                        @endif
                    </div>

                    {{-- alerts --}}
                    @if (session('success'))
                        <div class="mb-4 rounded-lg border border-green-300 bg-green-50 text-green-800 px-4 py-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
</body>

</html>
