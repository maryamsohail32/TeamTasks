<?php

namespace App\Http\Resources\Task;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'status'      => $this->status,
            'priority'    => $this->priority,
            'due_date'    => $this->due_date?->toDateString(),
            'is_overdue'  => $this->due_date && $this->due_date->isPast() && $this->status !== 'completed',
            'assigned_to' => new UserResource($this->whenLoaded('assignee')),
            'created_by'  => new UserResource($this->whenLoaded('creator')),
            'workspace_id' => $this->workspace_id,
            'created_at'  => $this->created_at->toISOString(),
            'updated_at'  => $this->updated_at->toISOString(),
        ];
    }
}
