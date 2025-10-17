<?php

namespace App\Http\Requests\Update;

use App\Enums\PlaceType;
use App\Http\Requests\Store\StorePlaceRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdatePlaceRequest extends StorePlaceRequest
{
}
