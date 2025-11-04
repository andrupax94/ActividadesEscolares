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
            'day' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday',
            'hour' => 'required|regex:/^\d{2}:\d{2} - \d{2}:\d{2}$/',
        ];
    }
}
