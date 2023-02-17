<?php

namespace AlphaDevTeam\AlphaCruds\Commands;

use AlphaDevTeam\AlphaCruds\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class CrudMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $name = 'alphacruds:make-crud';

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
        return $this->getModelName() . 's' . 'Controller';
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
