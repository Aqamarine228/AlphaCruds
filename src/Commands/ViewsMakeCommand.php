<?php

namespace AlphaDevTeam\AlphaCruds\Commands;

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
        $this->createCreateView($module);
        $this->createIndexView($module);
        $this->createShowView($module);
        $this->createEditView($module);
        $this->createFormView($module);
        $this->createTableView($module);
    }

    private function createCreateView(string $module): void
    {
        $this->call(
            'alphacruds:make-create-view',
            array_merge([
                'model' => $this->getModelName(),
                'module' => $module,
            ], $this->option('force') ? ['-f' => true] : [])
        );
    }
    private function createIndexView(string $module): void
    {
        $this->call(
            'alphacruds:make-index-view',
            array_merge([
                'model' => $this->getModelName(),
                'module' => $module,
            ], $this->option('force') ? ['-f' => true] : [])
        );
    }

    private function createShowView(string $module): void
    {
        $this->call(
            'alphacruds:make-show-view',
            array_merge([
                'model' => $this->getModelName(),
                'fields' => $this->getFieldsKeys(),
                'module' => $module,
            ], $this->option('force') ? ['-f' => true] : [])
        );
    }
    private function createEditView(string $module): void
    {
        $this->call(
            'alphacruds:make-edit-view',
            array_merge([
                'model' => $this->getModelName(),
                'module' => $module,
            ], $this->option('force') ? ['-f' => true] : [])
        );
    }

    private function createFormView(string $module): void
    {
        $this->call(
            'alphacruds:make-form-view',
            array_merge([
                'model' => $this->getModelName(),
                'fields' => $this->argument('fields'),
                'module' => $module,
            ], $this->option('force') ? ['-f' => true] : [])
        );
    }

    private function createTableView(string $module): void
    {
        $this->call(
            'alphacruds:make-table-view',
            array_merge([
                'model' => $this->getModelName(),
                'fields' => $this->getFieldsKeys(),
                'module' => $module,
            ], $this->option('force') ? ['-f' => true] : [])
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
        ];
    }
}
