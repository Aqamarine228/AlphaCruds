<?php

namespace AlphaDevTeam\AlphaCruds\Commands;

use AlphaDevTeam\AlphaCruds\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModelMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'model';

    protected $name = 'alphacruds:make-model';

    protected $description = 'Create a new model in AlphaDev style.';

    protected function getTemplateContents(): string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/model.stub', [
            'MODEL_NAME' => $this->getModelName(),
            'NAMESPACE' => $this->getClassNamespace($module) . '\\Models',
            'CLASS' => $this->getClass(),
            'PARENT_MODEL_NAMESPACE' => $this->getParentModelNamespace(),
        ]))->render();
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());
        $modelPath = GenerateConfigReader::read('model');
        return $path . $modelPath->getPath() . '/' . $this->getModelName() . '.php';
    }

    protected function getModelName(): string
    {
        return Str::studly($this->argument('model'));
    }

    protected function getParentModelNamespace(): string
    {
        return $this->option('parent') ? $this->option('parent') : 'App\\Models';
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
            ['parent', 'p', InputOption::VALUE_OPTIONAL, 'Change default parent model namespace']
        ];
    }
}
