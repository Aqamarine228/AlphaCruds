<?php

namespace AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands;

use AlphaDevTeam\AlphaCruds\Support\Stub;
use Symfony\Component\Console\Input\InputOption;

class CreateViewMakeCommand extends ViewMakeCommand
{
    protected $name = 'alphacruds:make-create-view';

    protected array $additionalOptions = [
        ['translations', 't', InputOption::VALUE_NONE, 'Fields that will be translated to multiple languages.'],
    ];

    protected function getTemplateContents(): bool|array|string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getViewStubName($this->getViewName()), [
            'LAYOUT' => $this->getLayout(),
            'ENTITY_NAME' => $this->getEntityName(),
            'MODEL_KEBAB' => $this->getModelKebabName(),
            'MODEL_CAMEL_NAME' => $this->getModelCamelName(),
            'LOWER_NAME' => $module->getLowerName(),
            'MODEL_NAMESPACE' => $this->getModelNamespace(),
            'MODEL_NAME' => $this->getModelName(),
        ]))->render();
    }

    private function getViewName(): string
    {
        return $this->option('translations') ? 'create-translated' : 'create';
    }

    protected function getViewPath(): string
    {
        return 'create';
    }
}