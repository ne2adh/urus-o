<div data-field-wrapper>
    <label for="archivo" class="block text-sm font-medium mb-1">Archivo (PDF)</label>
    <input id="archivo" name="archivo" type="file" accept="application/pdf"
           class="w-full rounded-xl bg-white border {{ $errors->has('archivo') ? 'border-rose-400' : 'border-slate-300' }} hover:border-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 shadow-sm px-3 py-2.5 transition">

    {{-- Mensaje de error para archivo --}}
    @error('archivo')
        <p class="server-error mt-1 text-xs sm:text-sm text-rose-600">{{ $message }}</p>
    @enderror
</div>
