<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
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
            'brand' => 'sometimes|string|max:255',
            'plate_number' => 'sometimes|unique:cars,plate_number,' . $this->car->id,
            'price_per_day' => 'sometimes|numeric|min:0',
            'description' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
