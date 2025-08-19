@extends('layouts.app')

@section('title','Editar resumen')
@section('content')
<div class="max-w-4xl">
  <form action="{{ route('resumen.update', $resumen) }}" method="POST" class="bg-white p-6 rounded-xl shadow space-y-6">
    @csrf
    @method('PUT')
    @include('resumen._form', ['resumen' => $resumen])
    <div class="flex items-center gap-2">
      <a href="{{ route('resumen.index') }}" class="px-4 py-2 rounded border">Cancelar</a>
      <button class="px-4 py-2 rounded bg-amber-500 text-white hover:bg-amber-600">Actualizar</button>
    </div>
  </form>
</div>
@endsection
