<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {viewName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $viewName = $this->argument('viewName');
        $viewPath = resource_path("views/{$viewName}.blade.php");

        if (File::exists($viewPath)) {
            $this->error('View already exists!');
            return;
        }

        $viewContent = <<<EOD
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Document</title>
        </head>
        <body>

        </body>
        </html>
        EOD;

        File::put($viewPath, $viewContent);
        $this->info("View created successfully: {$viewName}");
    }
}
