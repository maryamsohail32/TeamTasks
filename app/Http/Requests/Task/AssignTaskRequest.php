<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $workspace = $this->route('workspace');
        $memberIds = $workspace->allUsers()->pluck('id')->toArray();

        return [
            'user_id' => ['required', 'integer', Rule::in($memberIds)],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.in' => 'The user must be a member of this workspace.',
        ];
    }
}
