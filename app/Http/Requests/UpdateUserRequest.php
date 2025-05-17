<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'id' => ['required', 'integer'],
            'name' => ['sometimes', 'required', 'string'],
            'password' => ['sometimes', 'required', 'string'],
            'gender' => ['sometimes', 'required', 'integer', Rule::in([
                Gender::UNKNOWN->value,
                Gender::FEMALE->value,
                Gender::MALE->value,
            ])],
            'FirstName' => ['sometimes', 'required', 'string'],
            'LastName' => ['sometimes', 'required', 'string'],
            'avatar' => ['sometimes', 'required', 'string']
        ];
    }

}
