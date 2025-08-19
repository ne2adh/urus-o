@extends('layouts.app')

@section('title','Nuevo resumen')
@section('content')
<div class="max-w-4xl">
  <form action="{{ route('resumen.store') }}" method="POST" class="bg-white p-6 rounded-xl shadow space-y-6">
    @csrf
    @include('resumen._form')
    <div class="flex items-center gap-2">
      <a href="{{ route('resumen.index') }}" class="px-4 py-2 rounded border">Cancelar</a>
      <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Guardar</button>
    </div>
  </form>
</div>
@endsection
