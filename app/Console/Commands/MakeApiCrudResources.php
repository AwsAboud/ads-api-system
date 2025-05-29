<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakeApiCrudResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
  protected $signature = 'make:crud {name} {--f|force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate complete CRUD API resources: Service, Controller, Requests, Resource, and register routes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $controllerFolderPath = "Api/V1";
        $reqeustFolderpath = $name;

        // 1. Generate Service
        $this->call('make:service', [
            'name' => "{$name}Service",
            '--model' => $name,
        ]);

        // 2. Generate Controller with API options
        $this->call('make:controller', [
            'name' => "$controllerFolderPath/{$name}Controller",
            '--api' => true,
            '--model' => $name,
        ]);

        // 3. Generate Form Requests
        $this->call('make:request', ['name' => "{$reqeustFolderpath}/Store{$name}Request"]);
        $this->call('make:request', ['name' => "{$reqeustFolderpath}/Update{$name}Request"]);

        // 4. Generate API Resource
        $this->call('make:resource', ['name' => "{$name}Resource"]);
        
        $this->info('All API resources generated successfully.');
    }

}
