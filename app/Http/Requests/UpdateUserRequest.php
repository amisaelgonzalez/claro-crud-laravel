<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'name'      => ['required', 'string', 'max:100'],
            'phone'     => ['nullable', 'digits:10'],
            'birthday'  => ['required', 'date', 'before_or_equal:'.now()->subYears(18)->format('Y-m-d')],
            'city_id'   => ['required', 'exists:cities,id'],
        ];
    }
}
