<?php

namespace AlphaDevTeam\AlphaCruds\Commands;

use AlphaDevTeam\AlphaCruds\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $name = 'alphacruds:make-controller';

    protected $argumentName = 'model';

    protected $description = 'Creates CRUD Controller in AlphaDev style';

    protected function getTemplateContents(): string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'MODULENAME' => $module->getStudlyName(),
            'CONTROLLERNAME' => $this->getControllerName(),
            'NAMESPACE' => $module->getStudlyName(),
            'CLASS_NAMESPACE' => $this->getClassNamespace($module),
            'CLASS' => $this->getControllerNameWithoutNamespace(),
            'LOWER_NAME' => $module->getLowerName(),
            'MODULE' => $this->getModuleName(),
            'NAME' => $this->getModuleName(),
            'STUDLY_NAME' => $module->getStudlyName(),
            'MODULE_NAMESPACE' => $this->laravel['modules']->config('namespace'),
            'MODEL_NAME' => $this->getModelName(),
            'MODEL_CAMEL_NAME' => $this->getModelCamelName(),
            'MODEL_NAMESPACE' => $this->getModelNamespace(),
            'BASE_CONTROLLER' => $this->getBaseControllerPath($module->getStudlyName()),
            'REQUEST' => $this->getRequestPath(),
        ]))->render();
    }

    protected function getControllerName(): string
    {
        return $this->getModelName() . 'Controller';
    }

    private function getControllerNameWithoutNamespace(): string
    {
        return class_basename($this->getControllerName());
    }

    public function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $controllerPath = GenerateConfigReader::read('controller');

        return $path . $controllerPath->getPath() . '/' . $this->getControllerName() . '.php';
    }

    public function getDefaultNamespace(): string
    {
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.controller.namespace')
            ?: $module->config('paths.generator.controller.path', 'Http/Controllers');
    }

    protected function getStubName(): string
    {
        return '/controller.stub';
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
            ['model-namespace', 'mn', InputOption::VALUE_OPTIONAL, 'Namespace of model which will CRUD class use.'],
            ['base', 'b', InputOption::VALUE_OPTIONAL, 'Namespace of class which will CRUD class extend.'],
            ['request', 'r', InputOption::VALUE_OPTIONAL, 'Namespace of request which will CRUD class use for validation.'],
        ];
    }

    private function getModelCamelName(): string
    {
        return Str::camel($this->getModelName());
    }

    private function getModelName(): string
    {
        return Str::studly($this->argument('model'));
    }

    public function getModelNamespace(): string
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

    public function getRequestPath(): string
    {
        if ($this->option('request')) {
            return $this->option('request');
        }

        $path = $this->laravel['modules']->config('paths.generator.request.path', 'Http/Requests');
        $path = str_replace('/', '\\', $path);

        return $this->laravel['modules']->config('namespace')
            . '\\'
            . $this->laravel['modules']->findOrFail($this->getModuleName())
            . '\\'
            . $path
            . '\\'
            . $this->getModelName()
            . 's'
            . 'Request';
    }

    public function getBaseControllerPath(string $module): string
    {
        if ($this->option('base')) {
            return $this->option('base');
        }

        $path = $this->laravel['modules']->config('paths.generator.controller.path', 'Http/Controllers');
        $path = str_replace('/', '\\', $path);

        return $this->laravel['modules']->config('namespace')
            . '\\'
            . $this->laravel['modules']->findOrFail($this->getModuleName())
            . '\\'
            . $path
            . '\\'
            . 'Base'
            . $module
            . 'Controller';
    }
}
