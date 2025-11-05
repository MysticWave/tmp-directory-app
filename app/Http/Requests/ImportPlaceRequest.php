<?php

namespace App\Http\Requests;

use App\Enums\PlaceImportTaskType;
use App\Enums\PlaceImportType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ImportPlaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', new Enum(PlaceImportType::class)],
            'task_type' => [
                'required',
                'string',
                new Enum(PlaceImportTaskType::class),
            ],

            'query' => [
                'required_if:task_type,' . PlaceImportTaskType::URL->value,
                'string',
                'nullable',
            ],

            'category' => [
                'required_if:task_type,' . PlaceImportTaskType::PARAMS->value,
                'string',
                'nullable',
            ],
            'country' => [
                'required_if:task_type,' . PlaceImportTaskType::PARAMS->value,
                'string',
                'nullable',
            ],
            'city' => [
                'required_if:task_type,' . PlaceImportTaskType::PARAMS->value,
                'string',
                'nullable',
            ],
        ];
    }
}
