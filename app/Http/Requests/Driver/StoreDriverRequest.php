<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
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
            'name'        => 'required|string|max:255',
            'phone'       => 'required|string|min:11|max:14|unique:drivers,phone',
            'age'         => 'required|integer',
            'photo'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'address'     => 'required|string',
            'description' => 'nullable|string'
        ];
    }
}
