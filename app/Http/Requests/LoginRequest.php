<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username'  => ['required', 'min:3', 'max:50'],
            'password'  => ['required', Password::min(8)->letters() ->numbers()->symbols()->mixedCase() ]
        ];
    }

    protected function prepareForValidation() : void
    {
        $this->merge([
            'username' => strtolower($this->input('username'))
        ]);
    }

    protected function passedValidation() : void
    {
        $this->replace([
            'password'  => bcrypt($this->input('password'))
        ]);
    }


}
