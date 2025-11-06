<?php

namespace App\AI;

use App\AI\Clients\BaseAIClient;

class AIManager
{
    public static function client(
        ?string $service = null,
        ?string $model = null,
    ): BaseAIClient {
        $service = $service ?? config('ai.default');
        $config = config('ai.services.' . $service);

        if (!$config) {
            throw new \RuntimeException(
                "AI service configuration for '{$service}' not found.",
            );
        }

        if (!isset($config['driver'])) {
            throw new \RuntimeException(
                "AI service driver not configured for '{$service}'.",
            );
        }

        $driverClass = $config['driver'];

        /** @var BaseAIClient $client */
        $client = new $driverClass(
            array_merge($config, ['service' => $service]),
        );

        if ($model) {
            $client->withModel($model);
        }

        return $client;
    }
}
