@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="flex items-center justify-between mb-6">
  <h1 class="text-2xl font-semibold">Usuarios</h1>
  <a href="{{ route('usuarios.create') }}"
     class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Crear usuario</a>
</div>

<form method="GET" action="{{ route('usuarios.index') }}" class="mb-4">
  <div class="flex gap-2">
    <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por username, CI o nombre..."
           class="flex-1 border rounded px-3 py-2 focus:outline-none focus:ring">
    <button class="px-4 py-2 rounded bg-gray-800 text-white hover:bg-black">Buscar</button>
    @if($q)
      <a href="{{ route('usuarios.index') }}" class="px-4 py-2 rounded border">Limpiar</a>
    @endif
  </div>
</form>

<div class="overflow-x-auto bg-white rounded shadow">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-100 text-left">
      <tr>
        <th class="px-4 py-3">ID</th>
        <th class="px-4 py-3">Username</th>
        <th class="px-4 py-3">CI</th>
        <th class="px-4 py-3">Nombre completo</th>
        <th class="px-4 py-3">Email</th>
        <th class="px-4 py-3">Rol</th>
        <th class="px-4 py-3">Creado</th>
        <th class="px-4 py-3 text-right">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($usuarios as $u)
      <tr class="border-t">
        <td class="px-4 py-3">{{ $u->id }}</td>
        <td class="px-4 py-3">{{ $u->username }}</td>
        <td class="px-4 py-3">{{ $u->ci }}</td>
        <td class="px-4 py-3">{{ $u->nombre_completo }}</td>
        <td class="px-4 py-3">{{ $u->email }}</td>
        <td class="px-4 py-3">{{ $u->rol }}</td>
        <td class="px-4 py-3">{{ $u->created_at?->format('Y-m-d H:i') }}</td>
        <td class="px-4 py-3">
          <div class="flex justify-end gap-2">
            <a href="{{ route('usuarios.edit', $u) }}"
               class="px-3 py-1 rounded bg-amber-500 text-white hover:bg-amber-600">Editar</a>
            <form action="{{ route('usuarios.destroy', $u) }}" method="POST"
                  onsubmit="return confirm('¿Eliminar usuario #{{ $u->id }}?');">
              @csrf @method('DELETE')
              <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">Eliminar</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="px-4 py-6 text-center text-gray-500">Sin resultados</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">
  {{ $usuarios->links() }}
</div>
@endsection
