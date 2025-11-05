<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property int $place_id
 * @property ?string $place_name
 * @property float $rating
 * @property int $likes_count
 * @property int $reviews_count
 * @property ?string $text
 * @property ?string $original_text
 * @property array $pictures
 */
final class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return array_filter([
            'id' => $this->id,
            'place_id' => $this->place_id,
            'place_name' => $this->whenLoaded(
                'place',
                fn() => $this->place->name,
            ),
            'rating' => $this->rating,
            'likes_count' => $this->likes_count,
            'reviews_count' => $this->reviews_count,
            'text' => $this->text,
            'original_text' => $this->original_text ?? $this->text,
            'pictures' => $this->pictures ?? [],
        ]);
    }
}
