<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $input
 * @property string $status
 * @property string $type
 * @property ?string $output
 * @property ?int $tokens_used
 * @property string $outputable_type
 * @property string $outputable_name
 * @property string $outputable_href
 */
final class AiOutputResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return array_filter([
            'id' => $this->id,
            'status' => $this->status,
            'type' => last(explode('\\', $this->outputable_type)),
            'input' => $this->input,
            'output' => $this->output,
            'tokens_used' => $this->tokens_used,
            'outputable_name' =>
                $this->outputable->name ?? $this->outputable_id,
            'outputable_href' => $this->getOutputableLink(),
        ]);
    }
}
