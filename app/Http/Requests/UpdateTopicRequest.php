<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTopicRequest extends FormRequest {

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
            'title' => ['sometimes', 'required', 'string'],
            'parent_id' => ['sometimes', 'required', 'string'],
            'opened' => ['required', 'boolean'],
            'text' => ['sometimes', 'required', 'string']
        ];
    }

}
