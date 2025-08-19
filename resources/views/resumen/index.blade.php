@extends('layouts.app')

@section('title','Resumen diario')
@section('content')
<div class="flex items-center justify-between mb-6">
  <h2 class="text-xl font-semibold">Resumen diario</h2>
  <a href="{{ route('resumen.create') }}" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Nuevo</a>
</div>

<form method="GET" action="{{ route('resumen.index') }}" class="mb-4">
  <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
    <input type="text" name="q" value="{{ $q }}" placeholder="Provincia/Municipio/Circ."
           class="border rounded px-3 py-2 focus:outline-none focus:ring">
    <select name="user_id" class="border rounded px-3 py-2">
      <option value="">Todos los usuarios</option>
      @foreach($usuarios as $id => $u)
        <option value="{{ $id }}" @selected($uid == $id)>{{ $u }}</option>
      @endforeach
    </select>
    <input type="date" name="fecha" value="{{ $fecha }}" class="border rounded px-3 py-2">
    <button class="px-4 py-2 rounded bg-gray-800 text-white hover:bg-black">Filtrar</button>
  </div>
</form>

<div class="overflow-x-auto bg-white rounded-xl shadow">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-3 py-2">Fecha</th>
        <th class="px-3 py-2">Día</th>
        <th class="px-3 py-2">Usuario</th>
        <th class="px-3 py-2">Provincia</th>
        <th class="px-3 py-2">Municipio</th>
        <th class="px-3 py-2">Circ</th>
        <th class="px-3 py-2">Total día</th>
        <th class="px-3 py-2">Acum. usuario</th>
        <th class="px-3 py-2">% meta</th>
        <th class="px-3 py-2 text-right">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($resumenes as $r)
      <tr class="border-t">
        <td class="px-3 py-2 whitespace-nowrap">{{ $r->fecha?->format('Y-m-d') }}</td>
        <td class="px-3 py-2">{{ $r->numero_dia }}</td>
        <td class="px-3 py-2">{{ $r->user?->username }}</td>
        <td class="px-3 py-2">{{ $r->provincia }}</td>
        <td class="px-3 py-2">{{ $r->municipio }}</td>
        <td class="px-3 py-2">{{ $r->circunscripcion }}</td>
        <td class="px-3 py-2">{{ $r->total_dia }}</td>
        <td class="px-3 py-2">{{ $r->acum_user }}</td>
        <td class="px-3 py-2">{{ $r->porc_meta_user !== null ? number_format($r->porc_meta_user,2).'%' : '-' }}</td>
        <td class="px-3 py-2">
          <div class="flex justify-end gap-2">
            <a class="px-3 py-1 rounded bg-amber-500 text-white hover:bg-amber-600"
               href="{{ route('resumen.edit', $r) }}">Editar</a>
            <form action="{{ route('resumen.destroy', $r) }}" method="POST"
                onsubmit="return confirm('¿Eliminar registro #{{ $r->id }}?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                    Eliminar
                </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="10" class="px-3 py-6 text-center text-gray-500">Sin resultados</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">
  {{ $resumenes->links() }}
</div>
@endsection
