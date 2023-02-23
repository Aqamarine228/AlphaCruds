<?php

namespace AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ViewsMakeCommand extends Command
{
    use ModuleCommandTrait;

    protected $name = 'alphacruds:make-views';

    protected $description = 'Creates CRUD views in AlphaDev style';

    public function handle()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());
        if ($result = $this->createCreateView($module)) {
            return $result;
        }
        if ($result = $this->createIndexView($module)) {
            return $result;
        }
        if ($result = $this->createShowView($module)) {
            return $result;
        }
        if ($result = $this->createEditView($module)) {
            return $result;
        }
        if ($result = $this->createFormView($module)) {
            return $result;
        }
        if ($result = $this->createTableView($module)) {
            return $result;
        }
    }

    private function createCreateView(string $module): int
    {
        return $this->call(
            'alphacruds:make-create-view',
            array_merge(
                [
                    'model' => $this->getModelName(),
                    'module' => $module,
                ],
                $this->option('force') ? ['-f' => true] : [],
                $this->option('translations') ? ['-t' => true] : [],
            ),
        );
    }
    private function createIndexView(string $module): int
    {
        return $this->call(
            'alphacruds:make-index-view',
            array_merge([
                'model' => $this->getModelName(),
                'module' => $module,
            ], $this->option('force') ? ['-f' => true] : [])
        );
    }

    private function createShowView(string $module): int
    {
        return $this->call(
            'alphacruds:make-show-view',
            array_merge(
                [
                    'model' => $this->getModelName(),
                    'fields' => $this->getFieldsKeys(),
                    'module' => $module,
                ],
                $this->option('force') ? ['-f' => true] : [],
                $this->option('translations') ? ['-t' => $this->getTranslatedFieldsKeys()] : [],
            )
        );
    }
    private function createEditView(string $module): int
    {
        return $this->call(
            'alphacruds:make-edit-view',
            array_merge(
                [
                    'model' => $this->getModelName(),
                    'module' => $module,
                ],
                $this->option('force') ? ['-f' => true] : [],
                $this->option('translations') ? ['-t' => true] : [],
            )
        );
    }

    private function createFormView(string $module): int
    {
        return $this->call(
            'alphacruds:make-form-view',
            array_merge(
                [
                    'model' => $this->getModelName(),
                    'fields' => $this->argument('fields'),
                    'module' => $module,
                ],
                $this->option('force') ? ['-f' => true] : [],
                $this->option('translations') ? ['-t' => $this->option('translations')] : [],
            )
        );
    }

    private function createTableView(string $module): int
    {
        return $this->call(
            'alphacruds:make-table-view',
            array_merge(
                [
                    'model' => $this->getModelName(),
                    'fields' => $this->getFieldsKeys(),
                    'module' => $module,
                ],
                $this->option('force') ? ['-f' => true] : [],
                $this->option('translations') ? ['-t' => $this->getTranslatedFieldsKeys()] : [],
            )
        );
    }

    private function getModelName(): string
    {
        return Str::studly($this->argument('model'));
    }

    private function getFieldsKeys(): string
    {
        eval('$array=' . base64_decode($this->argument('fields')) . ';');

        if (sizeof($array) == 0) {
            return base64_encode('[]');
        }

        return base64_encode(
            '["'
            . implode('","', array_keys($array))
            . '"]'
        );
    }

    private function getTranslatedFieldsKeys(): string
    {
        eval('$array=' . base64_decode($this->option('translations')) . ';');

        if (sizeof($array) == 0) {
            return base64_encode('[]');
        }

        return base64_encode(
            '["'
            . implode('","', array_keys($array))
            . '"]'
        );
    }

    protected function getArguments(): array
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name of model to be used.'],
            ['fields', InputArgument::REQUIRED, 'Fields to be displayed in table.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Recreate existing views.'],
            ['translations', 't', InputOption::VALUE_OPTIONAL, 'Fields that will be translated to multiple languages.'],
        ];
    }
}
