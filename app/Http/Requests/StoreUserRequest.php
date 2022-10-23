<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              => ['required', 'string', 'max:100'],
            'email'             => ['required', 'string', 'email', 'unique:users,email'],
            'phone'             => ['nullable', 'digits:10'],
            'identification'    => ['required', 'string', 'max:11'],
            'birthday'          => ['required', 'date', 'before_or_equal:'.now()->subYears(18)->format('Y-m-d')],
            'password'          => [
                                    'required',
                                    'confirmed',
                                    Password::min(8) // Debe tener por lo menos 8 caracteres
                                        ->mixedCase() // Debe tener mayúsculas + minúsculas
                                        ->letters() // Debe incluir letras
                                        ->numbers() // Debe incluir números
                                        ->symbols(), // Debe incluir símbolos
                                    ],
            'city_id'           => ['required', 'exists:cities,id'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'birthday.before_or_equal' => 'The user must be under 18 years of age',
        ];
    }
}
