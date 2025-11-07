<?php

namespace App\Models;

use App\AI\AIManager;
use App\Enums\AiOutputStatus;
use App\Traits\OrderableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiOutput extends Model
{
    use OrderableTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'prompt_id',
        'status',
        'outputable_type',
        'outputable_id',
        'input',
        'output',
        'tokens_used',
        'response_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => AiOutputStatus::class,
        ];
    }

    public function outputable()
    {
        return $this->morphTo();
    }

    public function prompt(): BelongsTo
    {
        return $this->belongsTo(Prompt::class);
    }

    public function getOutputableLink(): ?string
    {
        return match ($this->outputable_type) {
            Place::class => route('places.show', $this->outputable_id),
            default => null,
        };
    }

    public function process(): bool
    {
        try {
            $response = AIManager::client()->getSingleResponse($this->input);

            $this->update([
                'output' => $response['response'] ?? null,
                'tokens_used' => $response['total_tokens_used'] ?? null,
                'response_id' => $response['id'] ?? null,
                'status' => AiOutputStatus::COMPLETED,
            ]);
            return true;
        } catch (\Exception $e) {
            report($e);
            $this->update(['status' => AiOutputStatus::FAILED]);
            return false;
        }
    }
}
