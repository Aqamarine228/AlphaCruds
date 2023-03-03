<?php

namespace Aqamarine\AlphaCruds\Commands;

use Aqamarine\AlphaCruds\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ApiTestMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'model';
    protected $name = 'alphacruds:make-api-test';
    protected $description = 'Create a new test class for the specified API CRUD.';


    protected function getTemplateContents(): string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/api-test.stub', [
            'NAMESPACE' => $this->getClassNamespace($module),
            'BASE_TEST_CASE' => $this->getBaseTestCasePath(),
            'MODEL_NAMESPACE' => $this->getModelNamespace(),
            'BASE_ROUTES' => $this->getBaseRoutes($module),
            'MODEL' => $this->getModelName(),
            'MODEL_CAMEL' => $this->getModelCamel(),
            'RESOURCE' => $this->getResourcePath(),
            'CLASS' => $this->getClassName(),
        ]))->render();
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());
        $testPath = GenerateConfigReader::read('test-feature');

        return $path . $testPath->getPath() . '/' . $this->getModelName() . 'Test.php';
    }

    public function getDefaultNamespace(): string
    {
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.test-feature.path', '/Tests/Feature');
    }

    private function getClassName(): string
    {
        return $this->getClass() . 'Test';
    }

    private function getBaseRoutes($module): string
    {
        return $module->getLowerName() . '.' . $this->getModelKebab();
    }

    private function getBaseTestCasePath(): string
    {
        if ($this->option('base')) {
            return $this->option('base');
        }

        return $this->laravel['modules']->config('namespace')
            . '\\'
            . $this->laravel['modules']->findOrFail($this->getModuleName())
            . '\\Tests\\'
            . $this->getModuleName()
            . 'TestCase';
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

    private function getModelName(): string
    {
        return Str::studly($this->argument('model'));
    }

    private function getModelKebab(): string
    {
        return Str::kebab($this->argument('model'));
    }

    private function getModelCamel(): string
    {
        return Str::camel($this->argument('model'));
    }

    protected function getArguments(): array
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name of the model for given test.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the test already exists'],
            ['base', '-b', InputOption::VALUE_OPTIONAL, 'Set custom base test path.'],
            ['model-namespace', 'mn', InputOption::VALUE_OPTIONAL, 'Namespace of model which will test class use.'],
            ['resource', 're', InputOption::VALUE_OPTIONAL, 'Namespace of resource which will be used.'],
        ];
    }
}
