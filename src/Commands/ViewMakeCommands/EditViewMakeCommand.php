<?php

namespace AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands;

use AlphaDevTeam\AlphaCruds\Support\Stub;

class EditViewMakeCommand extends ViewMakeCommand
{
    protected $name = 'alphacruds:make-edit-view';

    protected function getTemplateContents(): bool|array|string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getViewStubName('edit'), [
            'LAYOUT' => $this->getLayout(),
            'ENTITY_NAME' => $this->getEntityName(),
            'MODEL_KEBAB' => $this->getModelKebabName(),
            'MODEL_CAMEL_NAME' => $this->getModelCamelName(),
            'LOWER_NAME' => $module->getLowerName(),
        ]))->render();
    }

    protected function getViewPath(): string
    {
        return 'edit';
    }
}
