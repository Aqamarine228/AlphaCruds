<?php

namespace AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands;

use AlphaDevTeam\AlphaCruds\Support\Stub;
use Symfony\Component\Console\Input\InputOption;

class CreateViewMakeCommand extends ViewMakeCommand
{
    protected $name = 'alphacruds:make-create-view';

    protected function getTemplateContents(): bool|array|string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getViewStubName('create'), [
            'LAYOUT' => $this->getLayout(),
            'ENTITY_NAME' => $this->getEntityName(),
            'ENTITY_PLURAL_NAME' => $this->getEntityPluralName(),
            'MODEL_KEBAB' => $this->getModelKebabName(),
            'MODEL_CAMEL_NAME' => $this->getModelCamelName(),
            'LOWER_NAME' => $module->getLowerName(),
            'MODEL_NAMESPACE' => $this->getModelNamespace(),
            'MODEL_NAME' => $this->getModelName(),
        ]))->render();
    }

    protected function getViewPath(): string
    {
        return 'create';
    }
}
