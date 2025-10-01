<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'id'   => $this->id,
            'title' => $this->title,
            'priority' => $this->priority,
            'is_done' => $this->is_done,
            'due_date' => $this->due_date,
            'assignee' => $this->assignee?->name,
            'attachments' => AttachmentResource::collection($this->whenLoaded('attachments')),
        ];
    }
}
