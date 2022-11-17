<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

/**
 * Body parameters
 */
class UpdateUserTermsAndPoliciesRequest extends FormRequest
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
            'terms_conditions'  => ['required', 'boolean', 'accepted'],
            'privacy_policies'  => ['required', 'boolean', 'accepted'],
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
            'terms_conditions' => [
                'description'   => 'The user accepts the terms and conditions.',
                'example'       => 1,
            ],
            'privacy_policies' => [
                'description'   => 'The user accepts the privacy policies.',
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
