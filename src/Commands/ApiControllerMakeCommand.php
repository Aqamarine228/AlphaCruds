<?php

namespace Aqamarine\AlphaCruds\Commands;

use Aqamarine\AlphaCruds\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\Make\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ApiControllerMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $name = 'alphacruds:make-api-controller';

    protected $argumentName = 'model';

    protected $description = 'Creates CRUD API Controller in AlphaDev style';

    protected function getTemplateContents(): bool|array|string
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
            'MODEL_PLURAL_NAME' => $this->getModelPluralName(),
            'MODEL_NAMESPACE' => $this->getModelNamespace(),
            'BASE_CONTROLLER' => $this->getBaseControllerPath($module->getStudlyName()),
            'RESOURCE' => $this->getResourcePath(),
        ]))->render();
    }

    protected function getDestinationFilePath(): string
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
        return '/api-controller.stub';
    }

    private function getModelPluralName(): string
    {
        return Str::camel(Str::plural($this->getModelName()));
    }

    private function getModelCamelName(): string
    {
        return Str::camel($this->getModelName());
    }

    private function getModelName(): string
    {
        return Str::studly($this->argument('model'));
    }

    private function getControllerNameWithoutNamespace(): string
    {
        return class_basename($this->getControllerName());
    }

    protected function getControllerName(): string
    {
        return $this->getModelName() . 'Controller';
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

    public function getResourcePath(): string
    {
        if ($this->option('resource')) {
            return $this->option('resource');
        }

        $path = $this->laravel['modules']->config('paths.generator.resource.path', 'Http/Resources');
        $path = str_replace('/', '\\', $path);

        return $this->laravel['modules']->config('namespace')
            . '\\'
            . $this->laravel['modules']->findOrFail($this->getModuleName())
            . '\\'
            . $path
            . '\\'
            . $this->getModelName()
            . 'Resource';
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
            ['model-namespace', 'mn', InputOption::VALUE_OPTIONAL, 'Namespace of model which will CRUD class use.'],
            ['base', 'b', InputOption::VALUE_OPTIONAL, 'Namespace of class which will CRUD class extend.'],
            ['resource', 're', InputOption::VALUE_OPTIONAL, 'Namespace of resource which will be used.'],
        ];
    }
}
