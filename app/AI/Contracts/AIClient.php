<?php

namespace App\AI\Contracts;

interface AIClient
{
    public function withModel(string $model): static;

    public function listModels(): array;

    public function getResponse(
        array $messages,
        array $options = [],
        bool $fullResponse = false,
    ): array;

    public function getSingleResponse(
        string $query,
        string $instructions = '',
        array $options = [],
        bool $fullResponse = false,
    ): array;
}
