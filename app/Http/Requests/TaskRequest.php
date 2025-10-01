<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize()
    {
        return true; // policies handle deeper authorization
    }

    public function rules()
    {
        return [
            'title'      => 'required|string|max:255',
            'details'    => 'nullable|string',
            'priority'   => 'required|in:low,medium,high',
            'assignee_id'=> 'nullable|exists:users,id',
            'due_date'   => 'nullable|date',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Task title is required.',
            'priority.in'    => 'Invalid priority value.',
        ];
    }
}
    