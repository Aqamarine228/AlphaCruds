<?php

namespace AlphaDevTeam\AlphaCruds\Commands;

use AlphaDevTeam\AlphaCruds\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ResourceMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'model';
    protected $name = 'alphacruds:make-resource';
    protected $description = 'Create a new resource.';


    protected function getTemplateContents(): string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'NAMESPACE' => $this->getClassNamespace($module),
            'CLASS'     => $this->getClass(),
            'FIELDS'    => $this->generateFields(),
        ]))->render();
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $resourcePath = GenerateConfigReader::read('resource');

        return $path . $resourcePath->getPath() . '/' . $this->getModelName() . 'Resource' . '.php';
    }

    public function getClassNamespace($module): string
    {
        $extra = str_replace($this->getClass(), '', $this->getModelName() . 'Resource');

        $extra = str_replace('/', '\\', $extra);

        $namespace = $this->laravel['modules']->config('namespace');

        $namespace .= '\\' . $module->getStudlyName();

        $namespace .= '\\' . $this->getDefaultNamespace();

        $namespace .= '\\' . $extra;

        $namespace = str_replace('/', '\\', $namespace);

        return trim($namespace, '\\');
    }

    public function getClass(): string
    {
        return $this->getModelName() . 'Resource';
    }

    private function getModelName(): string
    {
        return Str::studly($this->argument('model'));
    }

    public function getDefaultNamespace(): string
    {
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.resource.namespace')
            ?: $module->config('paths.generator.resource.path', 'Http/Resources');
    }

    protected function getStubName(): string
    {
        return '/resource.stub';
    }

    private function generateFields(): string
    {
        $result = "";
        eval('$fields =' . $this->getFields() . ';');
        foreach ($fields as $field) {
            $result .= "
            '$field' => \$this->$field,";
        }
        return $result;
    }

    private function getFields(): string
    {
        return base64_decode($this->argument('fields'));
    }

    protected function getArguments(): array
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name of model to be used.'],
            ['fields', InputArgument::REQUIRED, 'Fields that will be used in resource.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the request already exists.'],
        ];
    }
}
