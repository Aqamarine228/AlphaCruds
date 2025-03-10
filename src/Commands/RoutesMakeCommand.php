<?php

namespace Aqamarine\AlphaCruds\Commands;

use Aqamarine\AlphaCruds\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\Make\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RoutesMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $name = 'alphacruds:make-routes';

    protected $argumentName = 'model';

    protected $description = 'Creates CRUD routes in AlphaDev style';
    protected function getTemplateContents(): bool|array|string
    {
        return (new Stub($this->getStubName(), [
            'CONTROLLER_PATH' => $this->getControllerPath(),
            'MODEL_KEBAB' => $this->getModelKebabName(),
            'MODEL_CAMEL' => $this->getModelCamelName(),
        ]))->render();
    }

    protected function getStubName(): string
    {
        return $this->option('translations') ? '/routes-translations.stub' : '/routes.stub';
    }

    protected function getControllerPath(): string
    {
        if ($this->option('controller')) {
            return $this->option('controller');
        }

        $path = $this->laravel['modules']->config('paths.generator.controller.path', 'Http/Controllers');
        $path = str_replace('/', '\\', $path);

        return $this->laravel['modules']->config('namespace')
            . '\\'
            . $this->laravel['modules']->findOrFail($this->getModuleName())
            . '\\'
            . $path
            . '\\'
            . $this->getModelName()
            . 'Controller';
    }
    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $controllerPath = GenerateConfigReader::read('routes');

        return $path . $controllerPath->getPath() . '/blocks/'. $this->getModelKebabName() . '.php';
    }

    protected function getModelCamelName(): string
    {
        return Str::camel($this->getModelName());
    }

    protected function getModelKebabName(): string
    {
        return Str::kebab($this->getModelName());
    }

    protected function getModelName(): string
    {
        return Str::studly($this->argument('model'));
    }

    protected function getArguments(): array
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name of model to be used.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the request already exists'],
            ['translations', 't', InputOption::VALUE_NONE, 'Whether to add update translated fields method to CRUD.'],
            ['controller', 'c', InputOption::VALUE_OPTIONAL, 'Path to controller which will CRUD routes use.'],
        ];
    }
}
