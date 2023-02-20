<?php

namespace AlphaDevTeam\AlphaCruds\Commands;

use AlphaDevTeam\AlphaCruds\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;

class TableViewMakeCommand extends ViewMakeCommand
{
    protected $name = 'alphacruds:make-table-view';

    protected array $additionalArguments = [
        ['fields', InputArgument::REQUIRED, 'Fields to be displayed in table.']
    ];
    protected function getTemplateContents(): bool|array|string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getViewStubName('_table'), [
            'MODEL_KEBAB' => $this->getModelKebabName(),
            'MODEL_CAMEL_NAME' => $this->getModelCamelName(),
            'LOWER_NAME' => $module->getLowerName(),
            'FIELDS' => $this->getFields(),
        ]))->render();
    }

    protected function getViewPath(): string
    {
        return 'blocks/_table';
    }

    private function getFields(): string
    {
        return base64_decode($this->argument('fields'));
    }
}
