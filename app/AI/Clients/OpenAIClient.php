<?php

namespace App\AI\Clients;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OpenAIClient extends BaseAIClient
{
    protected function authHeaders(): array
    {
        return [
            'Authorization' => "Bearer {$this->apiKey}",
        ];
    }

    public function getSingleResponse(
        string $query,
        string $instructions = '',
        array $options = [],
        bool $fullResponse = false,
    ): array {
        $messages = [];

        if ($instructions) {
            $messages[] = [
                'role' => 'system',
                'content' => $instructions,
            ];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $query,
        ];

        return $this->getResponse($messages, $options, $fullResponse);
    }

    public function getResponse(
        array $messages,
        array $options = [],
        bool $fullResponse = false,
    ): array {
        $payload = array_merge(
            [
                'model' => $this->getModel($options['model'] ?? null),
                'input' => $messages,
            ],
            $options,
        );

        $response = $this->http()->post(
            $this->config['endpoint'] ?? '/v1/chat/responses',
            $payload,
        );

        if ($response->failed()) {
            throw new \RuntimeException(
                'OpenAI API request failed: ' . $response->body(),
            );
        }

        $data = $response->json();

        if ($fullResponse) {
            return $data;
        }

        return [
            'total_tokens_used' => $data['usage']['total_tokens'] ?? 0,
            'response' => collect($data['output'])
                ->where('type', 'message')
                ->pluck('content.*.text')
                ->flatten()
                ->join("\n"),
            'created_at' => $data['created_at'] ?? null,
            'id' => $data['id'] ?? null,
            'status' => $data['status'] ?? null,
        ];
    }

    public function listModels(): array
    {
        // Try API first if enabled
        if (!empty($this->config['allow_remote_model_list'])) {
            try {
                return Cache::remember(
                    "openai_models_{$this->apiKey}",
                    now()->addHours(1),
                    function () {
                        $res = $this->http()
                            ->get('/v1/models')
                            ->throw()
                            ->json();
                        $models = collect($res['data'] ?? [])
                            ->pluck('id')
                            ->sort()
                            ->values()
                            ->toArray();

                        return $models;
                    },
                );
            } catch (\Throwable $e) {
                Log::warning(
                    'OpenAI model listing failed: ' . $e->getMessage(),
                );
            }
        }

        // Fallback to config
        return parent::listModels();
    }
}
