<?php

use App\AI\Clients\OpenAIClient;

return [
    'default' => 'openai',

    'services' => [
        'openai' => [
            'driver' => OpenAIClient::class,
            'api_key' => env('OPENAI_API_KEY'),
            'base_url' => 'https://api.openai.com',
            'endpoint' => '/v1/responses',
            'default_model' => 'gpt-5-nano',
            'models' => [
                'gpt-5',
                'gpt-5-mini',
                'gpt-5-nano',
                'gpt-4.1',
                'gpt-4.1-mini',
                'gpt-4.1-nano',
            ],
            'allow_remote_model_list' => true,
        ],
    ],
];
