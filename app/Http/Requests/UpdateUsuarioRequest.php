<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('usuario')->id ?? null;

        return [
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'ci' => 'required|string|max:20|unique:users,ci,' . $id,
            'nombre_completo' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'rol' => 'required|in:jefe,tecnico,superadministrador',
        ];
    }
}
