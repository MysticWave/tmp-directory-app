<?php

namespace App\Console\Commands\AI;

use App\Enums\AiOutputStatus;
use App\Models\AiOutput;
use Illuminate\Console\Command;

class Process extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process AI tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasks = AiOutput::where('status', AiOutputStatus::PENDING)->get();
        foreach ($tasks as $task) {
            $this->info(
                "Checking task ID: {$task->id}, Prompt ID: {$task->prompt_id}",
            );

            $task->update(['status' => AiOutputStatus::IN_PROGRESS]);
            if ($task->process()) {
                $this->info("Task ID: {$task->id} completed.");
                continue;
            }

            $this->error("Task ID: {$task->id} failed.");
        }
    }
}
