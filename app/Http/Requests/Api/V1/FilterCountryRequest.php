<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

/**
 * Query parameters
 */
class FilterCountryRequest extends FormRequest
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
            'term' => ['nullable', 'string'],
        ];
    }

    /**
     * Gets the descriptions and examples for each parameter in the query.
     *
     * @return array
     */
    public function queryParameters()
    {
        return [
            'term' => [
                'description'   => 'Search by country name.',
                'example'       => 'ecua',
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
