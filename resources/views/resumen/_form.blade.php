@php
  /** @var \App\Models\Resumen|null $resumen */
@endphp
<div class="space-y-4">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
      <label class="block text-sm font-medium mb-1">Fecha *</label>
      <input type="date" name="fecha" value="{{ old('fecha', isset($resumen)?$resumen->fecha?->format('Y-m-d'):'') }}" required
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">N° día *</label>
      <input type="number" min="1" max="366" name="numero_dia" value="{{ old('numero_dia', $resumen->numero_dia ?? '') }}" required
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Usuario *</label>
      <select name="user_id" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
        <option value="">-- seleccionar --</option>
        @foreach($usuarios as $id => $u)
          <option value="{{ $id }}" @selected(old('user_id', $resumen->user_id ?? '') == $id)>{{ $u }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
      <label class="block text-sm font-medium mb-1">Provincia *</label>
      <input type="text" name="provincia" value="{{ old('provincia', $resumen->provincia ?? '') }}" required
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" maxlength="100">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Municipio *</label>
      <input type="text" name="municipio" value="{{ old('municipio', $resumen->municipio ?? '') }}" required
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" maxlength="100">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Circunscripción *</label>
      <input type="number" min="0" name="circunscripcion" value="{{ old('circunscripcion', $resumen->circunscripcion ?? '') }}" required
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div>
      <label class="block text-sm font-medium mb-1">Total día *</label>
      <input type="number" min="0" name="total_dia" value="{{ old('total_dia', $resumen->total_dia ?? 0) }}" required
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Total día (prov) *</label>
      <input type="number" min="0" name="total_dia_prov" value="{{ old('total_dia_prov', $resumen->total_dia_prov ?? 0) }}" required
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Total día (mun) *</label>
      <input type="number" min="0" name="total_dia_mun" value="{{ old('total_dia_mun', $resumen->total_dia_mun ?? 0) }}" required
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Total día (circ) *</label>
      <input type="number" min="0" name="total_dia_circ" value="{{ old('total_dia_circ', $resumen->total_dia_circ ?? 0) }}" required
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div>
      <label class="block text-sm font-medium mb-1">Acum. usuario *</label>
      <input type="number" min="0" name="acum_user" value="{{ old('acum_user', $resumen->acum_user ?? 0) }}" required
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Acum. usuario (prov)</label>
      <input type="number" min="0" name="acum_user_prov" value="{{ old('acum_user_prov', $resumen->acum_user_prov ?? 0) }}"
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Acum. usuario (mun)</label>
      <input type="number" min="0" name="acum_user_mun" value="{{ old('acum_user_mun', $resumen->acum_user_mun ?? 0) }}"
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">% meta usuario</label>
      <input type="number" step="0.01" min="0" max="100" name="porc_meta_user" value="{{ old('porc_meta_user', $resumen->porc_meta_user ?? null) }}"
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" placeholder="0 - 100">
    </div>
  </div>
</div>
