<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255|unique:users,username',
            'ci' => 'required|string|max:20|unique:users,ci',
            'nombre_completo' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'name' => 'nullable|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:jefe,tecnico,superadministrador',
        ];
    }
}
