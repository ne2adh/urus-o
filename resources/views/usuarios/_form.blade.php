@php
    /** @var \App\Models\User|null $usuario */
@endphp
<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Username *</label>
            <input type="text" name="username" value="{{ old('username', $usuario->username ?? '') }}" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">CI *</label>
            <input type="text" name="ci" value="{{ old('ci', $usuario->ci ?? '') }}" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Nombre completo *</label>
        <input type="text" name="nombre_completo"
            value="{{ old('nombre_completo', $usuario->nombre_completo ?? '') }}" required
            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $usuario->email ?? '') }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Nombre (alias)</label>
            <input type="text" name="name" value="{{ old('name', $usuario->name ?? '') }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <label class="block text-sm font-medium mb-1">Rol *</label>
        <select name="rol" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
            @php $roles = ['jefe'=>'Jefe','tecnico'=>'Técnico','superadministrador'=>'Superadministrador']; @endphp
            @foreach ($roles as $val => $label)
                <option value="{{ $val }}" @selected(old('rol', $usuario->rol ?? '') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">
                Contraseña {{ isset($usuario) ? '(dejar vacío para no cambiar)' : '*' }}
            </label>
            <input type="password" name="password"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">
                Confirmar contraseña {{ isset($usuario) ? '' : '*' }}
            </label>
            <input type="password" name="password_confirmation"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
        </div>
    </div>
</div>
