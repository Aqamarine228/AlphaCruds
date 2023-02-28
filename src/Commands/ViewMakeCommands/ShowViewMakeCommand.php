<?php

namespace AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands;

use AlphaDevTeam\AlphaCruds\Support\Stub;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ShowViewMakeCommand extends ViewMakeCommand
{
    protected $name = 'alphacruds:make-show-view';

    protected array $additionalArguments = [
        ['fields', InputArgument::REQUIRED, 'Fields to be displayed in table.']
    ];

    protected array $additionalOptions = [
        ['components-path', 'cp', InputOption::VALUE_OPTIONAL, 'Path to required view components.'],
        ['translations', 't', InputOption::VALUE_OPTIONAL, 'Fields that will be translated to multiple languages.'],
    ];

    protected function getTemplateContents(): bool|array|string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getViewStubName($this->getViewName()), [
            'LAYOUT' => $this->getLayout(),
            'ENTITY_NAME' => $this->getEntityName(),
            'ENTITY_PLURAL_NAME' => $this->getEntityPluralName(),
            'MODEL_KEBAB' => $this->getModelKebabName(),
            'MODEL_CAMEL_NAME' => $this->getModelCamelName(),
            'LOWER_NAME' => $module->getLowerName(),
            'FIELDS' => $this->generateFields(),
            'TRANSLATED_FIELDS' => $this->getTranslatedFields() ? $this->generateTranslatedFields() : null,
        ]))->render();
    }

    protected function getViewPath(): string
    {
        return 'show';
    }

    private function getViewName(): string
    {
        return $this->getTranslatedFields() ? 'show-translated' : 'show';
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
    protected function generateTranslatedFields(): string
    {
        $result = "";
        $modelKebab = $this->getModelKebabName();
        eval('$fields=' . $this->getTranslatedFields() . ';');
        foreach ($fields as $field) {
            $title = Str::title($field);
            $result .= "
                        <tr>
                            <td>
                                $title
                            </td>
                            <td>
                                {{\$$modelKebab"."->translations->first()?->pivot->"."$field}}
                            </td>
                        </tr>";
        }
        return $result;
    }

    private function getFields(): string
    {
        return base64_decode($this->argument('fields'));
    }

    private function getTranslatedFields(): ?string
    {
        return $this->option('translations') ? base64_decode($this->option('translations')) : null;
    }
}
