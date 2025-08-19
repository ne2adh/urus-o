@extends('layouts.guest')

@section('title','Ingresar · URUS')

@section('content')
  <div class="mb-6 text-center">
    <div class="mx-auto h-12 w-12 rounded-xl bg-gray-900 text-white flex items-center justify-center text-lg font-bold">U</div>
    <h1 class="mt-3 text-2xl font-semibold">Iniciar sesión</h1>
    <p class="text-sm text-gray-500">Accede al panel administrativo</p>
  </div>

  <form action="{{ route('login.attempt') }}" method="POST" class="space-y-4 bg-white p-6 rounded-xl shadow">
    @csrf
    <div>
      <label class="block text-sm font-medium mb-1">Usuario (username)</label>
      <input type="text" name="username" value="{{ old('username') }}" required
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring"
             placeholder="usuario123">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Contraseña</label>
      <input type="password" name="password" required
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
    </div>
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="remember" class="rounded">
      <span class="text-sm text-gray-700">Recordarme</span>
    </label>

    @if ($errors->any())
      <div class="rounded border border-red-300 bg-red-50 text-red-800 px-3 py-2 text-sm">
        {{ $errors->first() }}
      </div>
    @endif

    <button class="w-full bg-gray-900 text-white rounded px-4 py-2 hover:bg-black">Entrar</button>
  </form>
@endsection
