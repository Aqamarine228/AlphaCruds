<?php

namespace AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands;

use AlphaDevTeam\AlphaCruds\Support\Stub;
use Symfony\Component\Console\Input\InputOption;

class IndexViewMakeCommand extends ViewMakeCommand
{
    protected $name = 'alphacruds:make-index-view';

    protected array $additionalOptions = [
        ['translations', 't', InputOption::VALUE_NONE, 'Whether to use translated version.'],
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
        ]))->render();
    }

    protected function getViewName(): string
    {
        return $this->option('translations') ? 'index-translated' : 'index';
    }

    protected function getViewPath(): string
    {
        return 'index';
    }
}
