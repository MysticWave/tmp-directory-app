<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class LobstrioService
{
    private string $apiBaseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->apiBaseUrl = config('services.lobstrio.api_base_url');
        $this->apiKey = config('services.lobstrio.api_key');
    }

    public function client(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Token ' . $this->apiKey,
        ])->baseUrl($this->apiBaseUrl);
    }

    public function listSquids(): mixed
    {
        return Cache::remember('lobstrio_squids', 60 * 15, function () {
            $response = $this->client()->get('/squids');
            return $response->json();
        });
    }

    public function addTasks(string $squidId, array $tasks): mixed
    {
        $response = $this->client()->post('/tasks', [
            'squid' => $squidId,
            'tasks' => $tasks,
        ]);

        return $response->json();
    }

    public function startRun(string $squidId): mixed
    {
        $response = $this->client()->post('/runs', [
            'squid' => $squidId,
        ]);
        return $response->json();
    }

    public function getRun(string $runId): mixed
    {
        $response = $this->client()->get("/runs/{$runId}");
        return $response->json();
    }

    public function getRunTasks(string $runId): mixed
    {
        $response = $this->client()->get('/runtasks', [
            'run' => $runId,
        ]);
        return $response->json();
    }

    public function deleteRunTasks(string $runId): bool
    {
        try {
            $tasks = $this->getRunTasks(runId: $runId);
            foreach ($tasks['data'] as $task) {
                $this->client()->delete("/tasks/{$task['task']}");
            }
            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function getResults(
        ?string $squidId = null,
        ?string $runId = null,
        int $page = 1,
    ): mixed {
        $query = [];
        if ($squidId !== null) {
            $query['squid'] = $squidId;
        }
        if ($runId !== null) {
            $query['run'] = $runId;
        }

        if (sizeof($query) !== 1) {
            throw new \InvalidArgumentException(
                'Either squidId OR runId must be provided.',
            );
        }

        $query['page'] = $page;

        $response = $this->client()->get('/results', $query);
        return $response->json();
    }

    public function getPlaceData(string $squidId, string $query): mixed
    {
        $tasks = [
            [
                'url' => $query,
            ],
        ];

        $response = $this->addTasks($squidId, $tasks);
        if (!isset($response['tasks'])) {
            logger()->error('Lobstr.io addTasks failed', [
                'response' => $response,
            ]);
            throw new \RuntimeException('Failed to add tasks to Lobstr.io.');
        }

        $response = $this->startRun($squidId);
        $runId = $response['id'] ?? null;
        if (!$runId) {
            logger()->error('Lobstr.io startRun failed', [
                'response' => $response,
            ]);
            throw new \RuntimeException('Failed to start run on Lobstr.io.');
        }

        return [
            'run_id' => $runId,
        ];
    }
}
