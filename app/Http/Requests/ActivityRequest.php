<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'days_string' => [
                'required',
                function ($attribute, $value, $fail) {
                    $diasValidos = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
                    $seleccionados = array_filter(explode(',', $value));
                    foreach ($seleccionados as $dia) {
                        if (!in_array($dia, $diasValidos)) {
                            $fail("El día \"$dia\" no es válido.");
                        }
                    }
                }
            ],

            'hour' => 'required|regex:/^\d{2}:\d{2} - \d{2}:\d{2}$/',
        ];
    }
}
