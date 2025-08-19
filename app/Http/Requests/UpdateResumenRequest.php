<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResumenRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('resumen')->id ?? null;

        return [
            'fecha' => ['required','date'],
            'numero_dia' => ['required','integer','min:1','max:366'],
            'user_id' => ['required','exists:users,id'],
            'provincia' => ['required','string','max:100'],
            'municipio' => ['required','string','max:100'],
            'circunscripcion' => ['required','integer','min:0','max:65535'],

            'total_dia' => ['required','integer','min:0'],
            'total_dia_prov' => ['required','integer','min:0'],
            'total_dia_mun' => ['required','integer','min:0'],
            'total_dia_circ' => ['required','integer','min:0'],

            'acum_user' => ['required','integer','min:0'],
            'acum_user_prov' => ['nullable','integer','min:0'],
            'acum_user_mun' => ['nullable','integer','min:0'],
            'acum_user_circ' => ['nullable','integer','min:0'],

            'porc_meta_user' => ['nullable','numeric','between:0,100'],

            Rule::unique('resumen')->ignore($id)->where(function ($q) {
                return $q->where('fecha', $this->input('fecha'))
                         ->where('numero_dia', $this->input('numero_dia'))
                         ->where('user_id', $this->input('user_id'))
                         ->where('provincia', $this->input('provincia'))
                         ->where('municipio', $this->input('municipio'))
                         ->where('circunscripcion', $this->input('circunscripcion'));
            }),
        ];
    }
}
