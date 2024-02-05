<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'street'        => ['required', 'string', 'min:5', 'max:100'],
            'rt'            => ['required', 'string', 'digits_between:3,10'],
            'rw'            => ['required', 'string', 'digits_between:3,10'],
            'city'          => ['required', 'string', 'min:3', 'max:100'],
            'province'      => ['required', 'string', 'min:4', 'max:100'],
            'country'       => ['required', 'string', 'min:3', 'max:100'],
            'postal_code'    => ['required', 'string', 'digits_between:3,10']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors' => $validator->errors(),
        ], 422));
    }
}
