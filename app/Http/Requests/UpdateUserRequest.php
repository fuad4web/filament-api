<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|string|max:55',
            //this is telling it to update email of that same particular person id but should be unique to the rest of the user
            'email' => 'required|email|unique:users,email,'.$this->id,
            'password' => [
                'confirmed',
                Password::min(8)->letters()->symbols()
            ]
        ];
    }
}
