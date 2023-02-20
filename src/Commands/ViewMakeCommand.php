<?php

namespace AlphaDevTeam\AlphaCruds\Commands;

use Illuminate\Support\Str;
use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class ViewMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'model';
    protected array $additionalArguments = [];

    protected array $additionalOptions = [];
    abstract protected function getViewPath(): string;

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $viewsPath = GenerateConfigReader::read('views');

        return $path
            . $viewsPath->getPath()
            . '/'
            . $this->getModelKebabName()
            .  '/' . $this->getViewPath()
            . '.blade.php';
    }

    protected function getViewStubName(string $name): string
    {
        return '/views/' . $name . '.stub';
    }

    protected function getModelCamelName(): string
    {
        return Str::camel($this->getModelName());
    }

    protected function getModelKebabName(): string
    {
        return Str::kebab($this->getModelName());
    }

    protected function getEntityName(): string
    {
        return Str::title($this->getModelName());
    }

    protected function getModelName(): string
    {
        return Str::studly($this->argument('model'));
    }

    protected function getArguments(): array
    {
        return array_merge([
            ['model', InputArgument::REQUIRED, 'The name of model to be used.'],
        ], $this->additionalArguments, [
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ]);
    }

    protected function getOptions(): array
    {
        return array_merge([
            ['model-namespace', 'mn', InputOption::VALUE_OPTIONAL, 'Namespace of model which will CRUD class use.'],
            ['layout', 'l', InputOption::VALUE_OPTIONAL, 'Layout which will be used for views.'],
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the request already exists'],
        ], $this->additionalOptions);
    }

    protected function getLayout(): string
    {
        return $this->option('layout') ? $this->option('layout') : 'layouts.master';
    }

    protected function getModelNamespace(): string
    {
        if ($this->option('model-namespace')) {
            return $this->option('model-namespace');
        }

        $path = $this->laravel['modules']->config('paths.generator.model.path', 'Models');
        $path = str_replace('/', '\\', $path);

        return $this->laravel['modules']->config('namespace')
            . '\\'
            . $this->laravel['modules']->findOrFail($this->getModuleName())
            . '\\'
            . $path;
    }
}
