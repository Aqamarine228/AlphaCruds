<?php

namespace AlphaDevTeam\AlphaCruds\Commands;

use AlphaDevTeam\AlphaCruds\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class FormViewMakeCommand extends ViewMakeCommand
{
    protected $name = 'alphacruds:make-form-view';

    protected array $additionalArguments = [
        ['fields', InputArgument::REQUIRED, 'Fields to be used in form.']
    ];

    protected array $additionalOptions = [
        ['components-path', 'cp', InputOption::VALUE_OPTIONAL, 'Path to required view components.'],
    ];
    protected function getTemplateContents(): bool|array|string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getViewStubName('_form'), [
            'MODEL_KEBAB' => $this->getModelKebabName(),
            'LOWER_NAME' => $module->getLowerName(),
            'FIELDS' => $this->getFields(),
            'COMPONENTS_PATH' => $this->getComponentsPath(),
        ]))->render();
    }

    protected function getViewPath(): string
    {
        return 'blocks/_form';
    }

    private function getFields(): string
    {
        return base64_decode($this->argument('fields'));
    }

    private function getComponentsPath(): string
    {
        return $this->option('components-path') ? $this->option('components-path') : 'components';
    }
}
