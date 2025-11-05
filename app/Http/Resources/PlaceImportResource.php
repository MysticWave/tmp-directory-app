<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property int $place_id
 * @property ?string $query
 * @property ?array $params
 * @property string $status
 * @property string $type
 * @property Carbon $created_at
 * @property \Illuminate\Database\Eloquent\Collection|PlaceResource[] $places
 */
final class PlaceImportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return array_filter([
            'id' => $this->id,
            'place_id' => $this->place_id,
            'query' => $this->query,
            'params' => $this->params,
            'status' => $this->status,
            'type' => $this->type,
            'created_at' => $this->created_at?->toDateTimeString(),
            'places' => PlaceResource::collection($this->whenLoaded('places')),
        ]);
    }
}
