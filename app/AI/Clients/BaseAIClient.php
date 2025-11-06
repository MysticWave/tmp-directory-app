<?php

namespace App\AI\Clients;

use App\AI\Contracts\AIClient;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

abstract class BaseAIClient implements AIClient
{
    protected string $service;
    protected string $baseUrl;
    protected string $apiKey;
    protected ?string $model = null;
    protected ?string $defaultModel = null;
    protected array $config = [];

    public function __construct(array $config)
    {
        $this->service = $config['service'] ?? 'unknown';
        $this->baseUrl = rtrim($config['base_url'] ?? '', '/');
        $this->apiKey = $config['api_key'] ?? '';
        $this->defaultModel = $config['default_model'] ?? null;
        $this->config = $config;
    }

    public function withModel(string $model): static
    {
        $this->model = $model;
        return $this;
    }

    public function listModels(): array
    {
        return $this->config['models'] ?? [];
    }

    protected function getModel(?string $override = null): string
    {
        return $override ??
            ($this->model ??
                ($this->defaultModel ??
                    throw new \RuntimeException(
                        "No model configured for {$this->service}.",
                    )));
    }

    protected function http(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->acceptJson()
            ->withHeaders($this->authHeaders())
            ->timeout($this->config['timeout'] ?? 60);
    }

    abstract protected function authHeaders(): array;

    abstract public function getResponse(
        array $messages,
        array $options = [],
        bool $fullResponse = false,
    ): array;

    abstract public function getSingleResponse(
        string $query,
        string $instructions = '',
        array $options = [],
        bool $fullResponse = false,
    ): array;
}
