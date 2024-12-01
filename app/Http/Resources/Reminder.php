<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use \App\Models\ReminderGroups;
use function App\Helpers\periodToBr;

class Reminder extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $group = ReminderGroups::where('id', $this->reminder_group_id)->first();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'reminder_date' => $this->reminder_date,
            'status' => $this->status,
            'repeat' => $this->repeat,
            'repeat_desc' => $this->repeat && $group ? periodToBr($group->period) : 'Não',
            'reference_group_id' => $this->reference_group_id,
            'reminder_type_id' => $this->reminder_type_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'group' => $group,
        ];
    }
}
