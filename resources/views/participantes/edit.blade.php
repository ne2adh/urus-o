@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-6">
        <h2 class="text-lg font-semibold mb-4">Editar Participante</h2>
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
        <form id="formRegistro" method="POST" action="{{ route('participantes.update', $participante) }}"
            enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div data-field-wrapper>
                    <label for="ci" class="block text-sm font-medium mb-1">CI <span
                            class="text-rose-600">*</span></label>
                    <input id="ci" name="ci" type="text" placeholder="Solo números (4–12 dígitos)"
                        value="{{ old('ci', $participante->ci) }}" required pattern="[0-9]{4,12}"
                        title="Entre 4 y 12 dígitos numéricos" aria-invalid="{{ $errors->has('ci') ? 'true' : 'false' }}"
                        class="w-full rounded-xl bg-white border {{ $errors->has('ci') ? 'border-rose-400' : 'border-slate-300' }} hover:border-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 shadow-sm px-3 py-2.5 placeholder:text-slate-400 transition">
                    @error('ci')
                        <p class="server-error mt-1 text-xs sm:text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div data-field-wrapper>
                    <label for="provincia" class="block text-sm font-medium mb-1">Provincia <span
                            class="text-rose-600">*</span></label>
                    <select id="provincia" name="provincia"
                        aria-invalid="{{ $errors->has('provincia') ? 'true' : 'false' }}"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">— Selecciona provincia —</option>
                        @foreach ($provinciasOruro as $p)
                            <option value="{{ $p }}" @selected(old('provincia', $participante->provincia) === $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                    @error('provincia')
                        <p class="server-error mt-1 text-xs sm:text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div data-field-wrapper>
                    <label for="municipio" class="block text-sm font-medium mb-1">Municipio <span
                            class="text-rose-600">*</span></label>
                    <select id="municipio" name="municipio"
                        aria-invalid="{{ $errors->has('municipio') ? 'true' : 'false' }}"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">— Selecciona municipio —</option>
                        @foreach ($municipiosOruro as $m)
                            <option value="{{ $m }}" @selected(old('municipio', $participante->municipio) === $m)>{{ $m }}</option>
                        @endforeach
                    </select>
                    @error('municipio')
                        <p class="server-error mt-1 text-xs sm:text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div data-field-wrapper>
                <label for="archivo" class="block text-sm font-medium mb-1">Archivo (PDF)</label>
                <input id="archivo" name="archivo" type="file" accept="application/pdf"
                    class="w-full rounded-xl bg-white border border-slate-300 hover:border-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 shadow-sm px-3 py-2.5 transition">
                @if ($participante->archivo)
                    <p class="mt-2 text-sm text-slate-600">
                        Archivo actual:
                        <a href="{{ asset('storage/participantes/' . $participante->archivo) }}" target="_blank"
                            class="text-blue-600 hover:underline">
                            Ver PDF
                        </a>
                    </p>
                @endif
                @error('archivo')
                    <p class="server-error mt-1 text-xs sm:text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 pt-2">
                <button type="submit"
                    class="rounded-xl bg-blue-600 text-white px-4 sm:px-5 py-2.5 hover:bg-blue-700 active:bg-blue-800 shadow-sm">
                    Guardar
                </button>
                <a href="{{ route('participantes.index') }}"
                    class="rounded-xl border border-slate-300 px-4 sm:px-5 py-2.5 hover:bg-slate-50 text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
