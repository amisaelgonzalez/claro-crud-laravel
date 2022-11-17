<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

/**
 * Body parameters
 * @bodyParam password_confirmation string required User password confirmation. Example: password
 */
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
            'terms_conditions'  => ['required', 'boolean', 'accepted'],
            'privacy_policies'  => ['required', 'boolean', 'accepted'],
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

    /**
     * Gets the descriptions and examples for each parameter in the body.
     *
     * @return array
     */
    public function bodyParameters()
    {
        return [
            'name' => [
                'description'   => 'User name.',
                'example'       => 'Jhon Doe',
            ],
            'email' => [
                'description'   => 'User email.',
                'example'       => 'user1@claroinsurance.com',
            ],
            'phone' => [
                'description'   => 'User phone.',
                'example'       => 9912345678,
            ],
            'identification' => [
                'description'   => 'User identification.',
                'example'       => '123456789-1',
            ],
            'birthday' => [
                'description'   => 'User birthday. Must be 18 years of age or older.',
                'example'       => '1995-01-23'
            ],
            'password' => [
                'description'   => 'User password.',
                'example'       => 'password',
            ],
            'password_confirmation' => [
                'description'   => 'Confirmation of user password.',
                'example'       => 'password',
            ],
            'terms_conditions' => [
                'description'   => 'The user accepts the terms and conditions.',
                'example'       => 1,
            ],
            'privacy_policies' => [
                'description'   => 'The user accepts the privacy policies.',
                'example'       => 1,
            ],
            'city_id' => [
                'description'   => 'The ID of the city.',
                'example'       => 1,
            ],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function failedValidation(Validator $validator)
    {
        $resp = [
            'msg'       => Lang::get('response.failed_validation_request'),
            'errors'    => (new ValidationException($validator))->errors()
        ];

        throw new HttpResponseException(response()->json($resp, 422));
    }
}
