@extends('layouts.app')

@section('title', 'Crear usuario')

@section('content')
<div class="max-w-2xl">
  <h1 class="text-2xl font-semibold mb-6">Crear usuario</h1>

  <form action="{{ route('usuarios.store') }}" method="POST" class="bg-white p-6 rounded shadow space-y-6">
    @csrf
    @include('usuarios._form')
    <div class="flex items-center gap-2">
      <a href="{{ route('usuarios.index') }}" class="px-4 py-2 rounded border">Cancelar</a>
      <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Guardar</button>
    </div>
  </form>
</div>
@endsection
