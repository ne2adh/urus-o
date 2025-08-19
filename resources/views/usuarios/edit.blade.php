@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')
<div class="max-w-2xl">
  <h1 class="text-2xl font-semibold mb-6">Editar usuario #{{ $usuario->id }}</h1>

  <form action="{{ route('usuarios.update', $usuario) }}" method="POST" class="bg-white p-6 rounded shadow space-y-6">
    @csrf
    @method('PUT')
    @include('usuarios._form', ['usuario' => $usuario])
    <div class="flex items-center gap-2">
      <a href="{{ route('usuarios.index') }}" class="px-4 py-2 rounded border">Cancelar</a>
      <button class="px-4 py-2 rounded bg-amber-500 text-white hover:bg-amber-600">Actualizar</button>
    </div>
  </form>
</div>
@endsection
