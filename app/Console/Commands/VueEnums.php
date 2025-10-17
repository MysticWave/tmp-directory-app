<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VueEnums extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vue:enums {--path=app/Enums} {--output=resources/js/Enum.js}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Vue Enums from PHP Enums';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->option('path');
        $output = $this->option('output');
        $namespace = 'App\Enums';

        $content = '';
        foreach (scandir($path) as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            $name = pathinfo($file, PATHINFO_FILENAME);
            $className = $namespace . '\\' . $name;
            $enum = [];
            foreach ($className::cases() as $case) {
                $enum[$case->name] = $case->value;
            }

            $content .=
                'export const ' .
                $name .
                ' = ' .
                json_encode($enum, JSON_PRETTY_PRINT) .
                ";\n\n";

            $this->info('Creating ' . $name . ' enum');
        }

        file_put_contents($output, $content);
        $this->info('Enums created successfully');
    }
}
