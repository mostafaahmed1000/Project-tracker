<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true; // handled by policies in controllers
    }

    public function rules()
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:planned,active,paused,completed',
            'due_date'    => 'nullable|date',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Project name is required.',
            'status.in'     => 'Invalid status selected.',
            'due_date.date' => 'Due date must be a valid date.',
        ];
    }
}
