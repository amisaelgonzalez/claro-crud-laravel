<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

/**
 * Body parameters
 * @bodyParam password_confirmation string required New user password confirmation. Example: password
 */
class UpdateUserRequest extends FormRequest
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
            'phone'             => ['nullable', 'digits:10'],
            'birthday'          => ['required', 'date', 'before_or_equal:'.now()->subYears(18)->format('Y-m-d')],
            'city_id'           => ['required', 'integer', 'exists:cities,id'],
            'current_password'  => ['required_with:password,password_confirmation', 'string', 'max:191'],
            'password'          => ['required_with:current_password,password_confirmation', 'string', 'min:8', 'max:191', 'confirmed'],
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
                'example'       => 'Jhon',
            ],
            'phone' => [
                'description'   => 'User phone.',
                'example'       => 9912345678,
            ],
            'birthday' => [
                'description'   => 'User birthday. Must be 18 years of age or older.',
                'example'       => '1995-01-23'
            ],
            'city_id' => [
                'description'   => 'The ID of the city.',
                'example'       => 1,
            ],
            'current_password' => [
                'description'   => "User's current password.",
                'example'       => 'cliuser1@claroinsurance.com',
            ],
            'password' => [
                'description'   => 'New user password.',
                'example'       => '12345678',
            ],
            'password_confirmation' => [
                'description'   => 'Confirmation of new user password.',
                'example'       => '12345678',
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
