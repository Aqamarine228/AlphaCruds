<?php

namespace AlphaDevTeam\AlphaCruds\Commands;

use AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands\ViewMakeCommand;
use AlphaDevTeam\AlphaCruds\Support\Stub;

class CreateViewMakeCommand extends ViewMakeCommand
{
    protected $name = 'alphacruds:make-create-view';

    protected function getTemplateContents(): bool|array|string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getViewStubName('create'), [
            'LAYOUT' => $this->getLayout(),
            'ENTITY_NAME' => $this->getEntityName(),
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
