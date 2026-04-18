<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name'           => 'required|string|max:255',
            'email'          => [
                'required',
                'email',
                Rule::unique('user_verifications')->where( function($query) {
                    return $query->whereIn('status', ['pending', 'approved']);
                }),
            ],
            'password'       => 'required|string|min:6',
            'phone_number'   => 'required|string|max:20',
            'address'        => 'required|string',
            'nik'            => [
                'required',
                'string',
                'max:16',
                Rule::unique('user_verifications')->where( function($query) {
                    return $query->whereIn('status', ['pending', 'approved']);
                }),
            ],
            'ktp_image'      => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'face_image'     => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'reject_reason'  => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email yang anda gunakan sudah terdaftar.',
            'nik.unique'   => 'NIK yang anda gunakan sudah terdaftar.',
            'face_image.max' => 'Foto yang di upload tidak boleh melebihi 5mb.',
            'ktp_image.max' => 'Foto yang di upload tidak boleh melebihi 5mb.',
            'face_image.mimes' => 'Foto yang di upload harus berformat png, jpg, jpeg.',
            'ktp_image.mimes' => 'Foto yang di upload harus berformat png, jpg, jpeg.',
        ];
    }
}
