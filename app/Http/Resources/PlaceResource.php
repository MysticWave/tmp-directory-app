<?php

namespace App\Http\Resources;

use App\Enums\PlaceType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property PlaceType $type
 * @property string|null $description
 * @property string $address_line_1
 * @property string|null $address_line_2
 * @property string $city
 * @property string|null $region
 * @property string|null $postcode
 * @property string $country
 * @property float $latitude
 * @property float $longitude
 * @property string|null $phone
 * @property string|null $website
 * @property string|null $email
 * @property float|null $rating
 * @property int $user_ratings_total
 * @property bool $is_verified
 * @property string $source
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class PlaceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return array_filter([
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'city' => $this->city,
            'region' => $this->region,
            'postcode' => $this->postcode,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'phone' => $this->phone,
            'website' => $this->website,
            'email' => $this->email,
            'rating' => $this->rating,
            'user_ratings_total' => $this->user_ratings_total,
            'is_verified' => $this->is_verified,
            'source' => $this->source,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ]);
    }
}
