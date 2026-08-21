<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore(
                    $this->user()?->id
                ),
            ],

            'avatar' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' =>
                'Nama wajib diisi.',

            'name.string' =>
                'Nama harus berupa teks.',

            'name.max' =>
                'Nama maksimal 255 karakter.',

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'email.max' =>
                'Email maksimal 255 karakter.',

            'email.unique' =>
                'Email sudah digunakan.',

            'avatar.file' =>
                'Avatar harus berupa file.',

            'avatar.mimes' =>
                'Avatar hanya boleh berupa JPG, JPEG, PNG, atau WEBP.',

            'avatar.max' =>
                'Ukuran avatar maksimal 2 MB.',
        ];
    }
}