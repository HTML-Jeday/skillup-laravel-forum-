<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'id' => ['required', 'string'],
            'name' => ['sometimes', 'required', 'string'],
            'password' => ['sometimes', 'required', 'string'],
            'gender' => ['sometimes', 'required', 'string'],
            'FirstName' => ['sometimes', 'required', 'string'],
            'LastName' => ['sometimes', 'required', 'string'],
            'avatar' => ['sometimes', 'required', 'string']
        ];
    }

}
