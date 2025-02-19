<?php

namespace Aqamarine\AlphaCruds\Commands;

use Aqamarine\AlphaCruds\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\Make\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RequestMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'model';

    protected $name = 'alphacruds:make-request';

    protected function getTemplateContents(): bool|array|string
    {
        return (new Stub('/request.stub', [
            'NAMESPACE' => $this->getRequestsNamespace(),
            'CLASS'     => $this->getClassName(),
            'CREATE_FIELDS' => $this->getCreateFields(),
            'UPDATE_FIELDS' => $this->getUpdateFields(),
        ]))->render();
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $requestPath = GenerateConfigReader::read('request');

        return $path . $requestPath->getPath() . '/' . $this->getModelName() . 'Request' . '.php';
    }

    private function getModelName(): string
    {
        return Str::studly($this->argument('model'));
    }

    private function getCreateFields(): string
    {
        return base64_decode($this->argument('create-fields'));
    }

    private function getClassName(): string
    {
        return $this->getModelName() . 'Request';
    }

    private function getRequestsNamespace(): string
    {
        $path = $this->laravel['modules']->config('paths.generator.request.path', 'Http/Requests');
        $path = str_replace('/', '\\', $path);

        return $this->laravel['modules']->config('namespace')
            . '\\'
            . $this->laravel['modules']->findOrFail($this->getModuleName())
            . '\\'
            . $path;
    }

    private function getUpdateFields(): string
    {
        return base64_decode($this->argument('update-fields'));
    }

    protected function getArguments(): array
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name of the form request class.'],
            ['create-fields', InputArgument::REQUIRED, 'Array of validation rules for creating model.'],
            ['update-fields', InputArgument::REQUIRED, 'Array of validation rules for updating model.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the request already exists'],
        ];
    }

}
