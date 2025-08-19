@extends('layouts.app')

@section('title', 'Participantes')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
        {{-- IZQUIERDA (1/3 en ≥lg): Formulario --}}
        <div class="lg:col-span-1 order-1 lg:order-1">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-semibold mb-4">Formulario de Registro</h2>

                @if (session('status'))
                    <div id="statusAlert"
                        class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-3 sm:px-4 py-2.5 sm:py-3 text-emerald-900 transition-all duration-500">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors instanceof \Illuminate\Support\ViewErrorBag && $errors->any())
                    <div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 px-3 sm:px-4 py-2.5 sm:py-3 text-rose-900">
                        <p class="font-medium">Revisa los campos:</p>
                        <ul class="mt-2 text-xs sm:text-sm list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formRegistro" method="POST" action="{{ route('participantes.store') }}"
                    enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div data-field-wrapper>
                            <label for="ci" class="block text-sm font-medium mb-1">CI <span
                                    class="text-rose-600">*</span></label>
                            <input id="ci" name="ci" type="text" placeholder="Solo números (4–12 dígitos)"
                                value="{{ old('ci') }}" required pattern="[0-9]{4,12}"
                                title="Entre 4 y 12 dígitos numéricos"
                                aria-invalid="{{ $errors->has('ci') ? 'true' : 'false' }}"
                                class="w-full rounded-xl bg-white border {{ $errors->has('ci') ? 'border-rose-400' : 'border-slate-300' }} hover:border-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 shadow-sm px-3 py-2.5 placeholder:text-slate-400 transition">
                            @error('ci')
                                <p class="server-error mt-1 text-xs sm:text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div data-field-wrapper>
                            <label for="provincia" class="block text-sm font-medium mb-1">Provincia<span
                                    class="text-rose-600">*</span></label>
                            <select id="provincia" name="provincia"
                                aria-invalid="{{ $errors->has('provincia') ? 'true' : 'false' }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">— Selecciona provincia —</option>
                                @foreach ($provinciasOruro as $p)
                                    <option value="{{ $p }}" @selected(old('provincia') === $p)>{{ $p }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div data-field-wrapper>
                            <label for="municipio" class="block text-sm font-medium mb-1">Municipio<span
                                    class="text-rose-600">*</span></label>
                            <select id="municipio" name="municipio"
                                aria-invalid="{{ $errors->has('municipio') ? 'true' : 'false' }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">— Selecciona municipio —</option>
                                @foreach ($municipiosOruro as $m)
                                    <option value="{{ $m }}" @selected(old('municipio') === $m)>{{ $m }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div data-field-wrapper>
                        <label for="archivo" class="block text-sm font-medium mb-1">Archivo (PDF)</label>
                        <input id="archivo" name="archivo" type="file" accept="application/pdf"
                            class="w-full rounded-xl bg-white border border-slate-300 hover:border-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 shadow-sm px-3 py-2.5 transition">
                        @error('archivo')
                            <p class="server-error mt-1 text-xs sm:text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 pt-2">
                        <button type="submit"
                            class="rounded-xl bg-blue-600 text-white px-4 sm:px-5 py-2.5 hover:bg-blue-700 active:bg-blue-800 shadow-sm">
                            Guardar
                        </button>
                        <button type="reset"
                            class="rounded-xl border border-slate-300 px-4 sm:px-5 py-2.5 hover:bg-slate-50">
                            Limpiar
                        </button>
                    </div>
                </form>
            </div>
            {{-- KPIs por usuario --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 mt-2">
<div class="bg-white rounded-xl shadow p-5">
    <div class="text-lg text-blue-500 mb-1">TOTAL DÍA</div>
    <div class="text-3xl font-semibold">{{ number_format($kpisUser['totalDia']) }}</div>
</div>
<div class="bg-white rounded-xl shadow p-5">
    <div class="text-lg text-blue-500 mb-1">TOTAL ACUMULADO</div>
    <div class="text-3xl font-semibold">{{ number_format($kpisUser['totalAcumulado']) }}</div>
</div>
</div>
        </div>

        {{-- DERECHA (2/3 en ≥lg): Listado CRUD --}}
        <div class="lg:col-span-2 order-2 lg:order-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <h2 class="text-lg sm:text-xl font-semibold">Listado de Participantes</h2>
                    <form method="GET" action="{{ route('participantes.index') }}" class="flex items-center gap-2">
                        <input type="text" name="q" value="{{ $q }}"
                            placeholder="Buscar por CI o nombre"
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-600 w-48 sm:w-64">
                        <button class="px-4 py-2 text-sm rounded-lg bg-gray-800 text-white hover:bg-black">Buscar</button>
                        @if ($q)
                            <a href="{{ route('participantes.index') }}"
                                class="px-3 py-2 text-sm rounded-lg border">Limpiar</a>
                        @endif
                    </form>
                </div>

                <div class="-mx-4 sm:mx-0 overflow-x-auto">
                    <table class="min-w-[640px] sm:min-w-full text-xs sm:text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-3 py-2 text-left">ID</th>
                                <th class="px-3 py-2 text-left">CI</th>
                                {{-- <th class="px-3 py-2 text-left">Nombre</th>
                                <th class="px-3 py-2 text-left">Fecha nac.</th> --}}
                                <th class="px-3 py-2 text-left">Provincia</th>
                                <th class="px-3 py-2 text-left">Municipio</th>
                                <th class="px-3 py-2 text-left">Archivo</th>
                                <th class="px-3 py-2 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($participantes as $p)
                                <tr class="border-t">
                                    <td class="px-3 py-2">{{ $p->id }}</td>
                                    <td class="px-3 py-2">{{ $p->ci }}</td>
                                    {{-- <td class="px-3 py-2">{{ $p->nombre_completo }}</td>
                                    <td class="px-3 py-2">
                                        {{ $p->fecha_nac ? \Carbon\Carbon::parse($p->fecha_nac)->format('Y-m-d') : '-' }}
                                    </td> --}}
                                    <td class="px-3 py-2">{{ $p->provincia }}</td>
                                    <td class="px-3 py-2">{{ $p->municipio }}</td>
                                    <td class="px-3 py-2">
                                        @if (!empty($p->archivo))
                                            <a href="{{ route('files.show', ['path' => $p->archivo]) }}"
                                                class="text-blue-600 hover:underline" target="_blank">
                                                Ver PDF
                                            </a>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    @auth
                                        @if (auth()->user()->rol === 'superadministrador')
                                            <td class="px-3 py-2">
                                                <div class="flex justify-end gap-2">
                                                    <form action="{{ route('participantes.destroy', $p->id) }}" method="POST"
                                                        onsubmit="return confirm('¿Eliminar participante #{{ $p->id }}?');">
                                                        @csrf @method('DELETE')
                                                        <button
                                                            class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">Eliminar</button>
                                                    </form>
                                                </div>
                                            </td>
                                        @else
                                            <td class="px-3 py-2">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('participantes.edit', $p->id) }}"
                                                        class="px-3 py-1 rounded bg-amber-500 text-white hover:bg-amber-600">Editar</a>
                                                </div>
                                            </td>
                                        @endif
                                    @endauth
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-6 text-center text-gray-500">Sin resultados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $participantes->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- UX scripts --}}
    <script>
        const ok = document.getElementById('statusAlert');
        if (ok) setTimeout(() => ok.classList.add('hidden', 'translate-y-1'), 2500);

        const form = document.getElementById('formRegistro');
        if (form) {
            form.querySelectorAll('input,select,textarea').forEach((el) => {
                el.addEventListener('input', () => {
                    const wrap = el.closest('[data-field-wrapper]');
                    if (!wrap) return;
                    const serverErr = wrap.querySelector('.server-error');
                    if (serverErr) serverErr.classList.add('hidden');
                    el.classList.remove('border-rose-400');
                    el.classList.add('border-slate-300');
                });
            });
        }
    </script>
@endsection
