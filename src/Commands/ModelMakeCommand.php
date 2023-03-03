<?php

namespace Aqamarine\AlphaCruds\Commands;

use Aqamarine\AlphaCruds\Support\Stub;
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

        if (!$this->option('without-base') && !class_exists($this->getParentModelNamespace())) {
            $this->createDefaultModel();
        }

        return (new Stub($this->getStubName(), [
            'MODEL_NAME' => $this->getModelName(),
            'NAMESPACE' => $this->getClassNamespace($module) . '\\Models',
            'CLASS' => $this->getClass(),
            'PARENT_MODEL_NAMESPACE' => $this->getParentModelNamespace(),
            'FILLABLE' => $this->getFillable(),
            'TRANSLATED_FIELDS' => $this->getTranslatedFields(),
            'MODEL_SNAKE' => $this->getModelSnakeName(),
        ]))->render();
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());
        $modelPath = GenerateConfigReader::read('model');
        return $path . $modelPath->getPath() . '/' . $this->getModelName() . '.php';
    }

    private function createDefaultModel()
    {
        $this->call('alphacruds:make-base-model', [
            'name' => $this->getModelName(),
        ]);
    }

    private function getStubName(): string
    {
        return $this->getTranslatedFields() ? '/model-translations.stub' : '/model.stub';
    }

    protected function getModelName(): string
    {
        return Str::studly($this->argument('model'));
    }

    protected function getParentModelNamespace(): string
    {
        return $this->option('parent') ? $this->option('parent') : 'App\\Models';
    }

    private function getFillable(): string
    {
        return base64_decode($this->argument('fillable'));
    }

    private function getTranslatedFields(): ?string
    {
        return $this->option('translations') ? base64_decode($this->option('translations')) : null;
    }

    private function getModelSnakeName(): string
    {
        return Str::snake($this->argument('model'));
    }


    protected function getArguments(): array
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name of model to be used.'],
            ['fillable', InputArgument::REQUIRED, 'Fields to be fillable in model.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the request already exists.'],
            ['parent', 'p', InputOption::VALUE_OPTIONAL, 'Change default parent model namespace.'],
            ['translations', 't', InputOption::VALUE_OPTIONAL, 'Fields that will be translated to multiple languages.'],
            ['without-base', 'wb', InputOption::VALUE_NONE, 'Will not create base model if not already created.']
        ];
    }
}
