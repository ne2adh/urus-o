<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $usuarios = User::query()
            ->when($q, function ($query) use ($q) {
                $query->where('username', 'like', "%{$q}%")
                      ->orWhere('ci', 'like', "%{$q}%")
                      ->orWhere('nombre_completo', 'like', "%{$q}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('usuarios.index', compact('usuarios', 'q'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'ci' => 'required|string|max:20|unique:users,ci',
            'nombre_completo' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'name' => 'nullable|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $usuario->id,
            'ci' => 'required|string|max:20|unique:users,ci,' . $usuario->id,
            'nombre_completo' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $usuario->id,
            'name' => 'nullable|string|max:255',
            'rol' => 'required|in:jefe,tecnico,superadministrador',
        ]);

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6|confirmed';
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $usuario->update($validated);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
