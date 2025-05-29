<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeServiceCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name : The name of the service class} {--model= : The associated Eloquent model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return base_path('stubs/service.model.stub');
    }

    /**
     * Get the default namespace for the class.
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\\Services';
    }

    /**
     * Build the class with the given name.
     */
    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);

        $model = $this->option('model') ?: str_replace('Service', '', class_basename($name));

        $model = Str::studly($model);
        $modelVariable = Str::camel($model);
        $namespacedModel = $this->qualifyModel($model);

        $replacements = [
            '{{ model }}' => $model,
            '{{ modelVariable }}' => $modelVariable,
            '{{ namespacedModel }}' => $namespacedModel,
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $stub
        );
    }

    /**
     * Qualify the given model class base name.
     */
    protected function qualifyModel(string $model): string
    {
        $model = ltrim($model, '\\');

        return str_starts_with($model, $this->rootNamespace())
            ? $model
            : $this->rootNamespace() . 'Models\\' . $model;
    }
}
