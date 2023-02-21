<?php

namespace AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands;

use AlphaDevTeam\AlphaCruds\Support\Stub;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class ShowViewMakeCommand extends ViewMakeCommand
{
    protected $name = 'alphacruds:make-show-view';

    protected array $additionalArguments = [
        ['fields', InputArgument::REQUIRED, 'Fields to be displayed in table.']
    ];

    protected function getTemplateContents(): bool|array|string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getViewStubName('show'), [
            'LAYOUT' => $this->getLayout(),
            'ENTITY_NAME' => $this->getEntityName(),
            'MODEL_KEBAB' => $this->getModelKebabName(),
            'MODEL_CAMEL_NAME' => $this->getModelCamelName(),
            'LOWER_NAME' => $module->getLowerName(),
            'FIELDS' => $this->generateFields(),
        ]))->render();
    }

    protected function getViewPath(): string
    {
        return 'show';
    }

    protected function generateFields(): string
    {
        $result = "";
        $modelKebab = $this->getModelKebabName();
        eval('$fields=' . $this->getFields() . ';');
        foreach ($fields as $field) {
            $title = Str::title($field);
            $result .= "
                        <tr>
                            <td>
                                $title
                            </td>
                            <td>
                                {{\$$modelKebab->$field}}
                            </td>
                        </tr>";
        }
        return $result;
    }

    private function getFields(): string
    {
        return base64_decode($this->argument('fields'));
    }
}
