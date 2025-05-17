<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\TopicStatus;

class CreateTopicRequest extends FormRequest {

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
            'title' => ['required', 'string'],
            'parent_id' => ['required', 'string'],
            'opened' => ['boolean'], // Keep for backward compatibility
            'status' => ['integer', 'in:' . implode(',', TopicStatus::values())],
            'text' => ['required', 'string']
        ];
    }

}
