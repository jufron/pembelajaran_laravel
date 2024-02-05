<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactCreateRequest extends FormRequest
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
            'firstName' => ['required', 'max:50', 'min:3'],
            'lastname'  => ['required', 'max:50', 'min:4'],
            'email'     => ['nullable', 'unique:contacts,email', 'email', 'max:200', 'min:10'],
            'phone'     => ['nullable', 'unique:contacts,phone', 'numeric', 'digits_between:11,13']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors' => $validator->errors(),
        ], 422));
    }
}
