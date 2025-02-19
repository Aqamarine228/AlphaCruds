<?php

namespace Aqamarine\AlphaCruds\Commands;

use Aqamarine\AlphaCruds\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\Make\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class FactoryMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'model';

    protected $name = 'alphacruds:make-factory';

    protected $description = 'Create a new model factory for the specified model.';
    protected function getTemplateContents(): bool|array|string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/factory.stub', [
            'NAMESPACE' => $this->getClassNamespace($module),
            'MODEL' => $this->getModelName(),
            'MODEL_NAMESPACE' => $this->getModelNamespace(),
            'FIELDS' => $this->generateFields(),
        ]))->render();
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $factoryPath = GenerateConfigReader::read('factory');

        return $path . $factoryPath->getPath() . '/' . $this->getFileName();
    }

    public function getDefaultNamespace(): string
    {
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.factory.namespace') ?: $module->config('paths.generator.factory.path');
    }

    private function getFileName(): string
    {
        return $this->getModelName() . 'Factory.php';
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

    private function generateFields(): string
    {
        $result = "";
        eval('$fields=' . $this->getFields() . ';');
        foreach ($fields as $field => $type) {
            $result .= "
            '$field' => " . $this->typeToFaker($type[0]) . ',';
        }
        return $result;
    }

    private function typeToFaker(string $type): string
    {
        return match ($type) {
            'text' => '\Str::random()',
            'number' => '$this->faker->randomNumber(5, false)',
        };
    }

    private function getModelName(): string
    {
        return Str::studly($this->argument('model'));
    }

    private function getFields(): string
    {
        return base64_decode($this->argument('fields'));
    }

    protected function getArguments(): array
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name of model to be used.'],
            ['fields', InputArgument::REQUIRED, 'Fields to be generated in factory.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the request already exists'],
            ['model-namespace', 'mn', InputOption::VALUE_OPTIONAL, 'Namespace of model which will factory class use.'],
        ];
    }
}
