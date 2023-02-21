<?php

namespace AlphaDevTeam\AlphaCruds\Commands;

use AlphaDevTeam\AlphaCruds\Support\Stub;
use Illuminate\Support\Str;
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
            'TABLE_HEADERS' => $this->generateTableHeaders(),
            'TABLE_BODY' => $this->generateTableBody(),
        ]))->render();
    }

    protected function generateTableHeaders(): string
    {
        $result = "";
        eval('$fields=' . $this->getFields() . ';');
        foreach ($fields as $field) {
            $title = Str::title($field);
            $result .= "
        <th>$title</th>";
        }
        return $result;
    }

    protected function generateTableBody(): string
    {
        $result = "";
        $modelKebab = $this->getModelKebabName();
        eval('$fields=' . $this->getFields() . ';');
        foreach ($fields as $field) {
            $result .= "
            <td>{{\$$modelKebab->$field}}</td>";
        }
        return $result;
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
