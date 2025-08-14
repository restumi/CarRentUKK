<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UserVerificationRequest extends FormRequest
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
        'name' => 'required|string|max:255',
        'email' => 'required|email:rfc,dns|unique:user_verifikations,email|unique:users,email',
        'password' => 'required|confirmed|min:6',
        'role' => 'nullable',
        'phone_number' => 'required',
        'address' => 'required|string',
        'nik' => 'required|string|max:20|min:20|unique:user_verifikations,nik|unique:users,nik',
        'ktp_image' => 'required|image|mimes:png,jpg,jpeg|max:4096',
        'face_image' => 'required|image|mimes:png,jpg,jpeg|max:4096',
        ];
    }
}
