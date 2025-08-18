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
            'ktp_image'      => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'face_image'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'reject_reason'  => 'nullable|string'
        ];
    }
}
