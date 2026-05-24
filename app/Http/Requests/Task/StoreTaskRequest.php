<?php

namespace App\Http\Requests\Task;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $workspace = $this->route('workspace');

        // Assignee must be a member of the workspace
        $memberIds = $workspace->allUsers()->pluck('id')->toArray();

        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'priority'    => ['nullable', Rule::in([Task::PRIORITY_LOW, Task::PRIORITY_MEDIUM, Task::PRIORITY_HIGH])],
            'due_date'    => ['nullable', 'date', 'after_or_equal:today'],
            'assigned_to' => ['nullable', 'integer', Rule::in($memberIds)],
        ];
    }

    public function messages(): array
    {
        return [
            'assigned_to.in' => 'The assigned user must be a member of this workspace.',
        ];
    }
}
