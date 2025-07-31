<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|min:10|max:14|unique:drivers,phone',
            'age' => 'sometimes|integer',
            'photo' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            'address' => 'sometimes|string',
            'description' => 'sometimes|string'
        ];
    }
}
