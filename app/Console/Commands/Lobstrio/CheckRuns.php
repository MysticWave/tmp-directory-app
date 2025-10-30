<?php

namespace App\Console\Commands\Lobstrio;

use App\Enums\PlaceImportStatus;
use App\Models\PlaceImport;
use App\Services\LobstrioService;
use Illuminate\Console\Command;

class CheckRuns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lobstrio:check-runs {--run-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the status of Lobstr.io runs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $runId = $this->option('run-id');
        if ($runId) {
            dd(
                app(LobstrioService::class)->getRun(runId: $runId),
                app(LobstrioService::class)->getRunTasks(runId: $runId),
                app(LobstrioService::class)->getResults(runId: $runId),
            );
            return 0;
        }

        $imports = PlaceImport::where(
            'status',
            PlaceImportStatus::IN_PROGRESS,
        )->get();
        foreach ($imports as $import) {
            $this->info(
                "Checking import ID: {$import->id}, Run ID: {$import->run_id}",
            );
            $completed = $import->check();
            if ($completed) {
                $this->info("Import ID: {$import->id} completed.");
                continue;
            }

            $this->info("Import ID: {$import->id} still in progress.");
        }
    }
}
