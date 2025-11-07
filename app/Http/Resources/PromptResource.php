<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property ?string $description
 * @property string $type
 * @property string $prompt_text
 */
final class PromptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return array_filter([
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'prompt_text' => $this->prompt_text,
        ]);
    }
}
