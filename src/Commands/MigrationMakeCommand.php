<?php

namespace Aqamarine\AlphaCruds\Commands;

use Aqamarine\AlphaCruds\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\Make\GeneratorCommand;
use Nwidart\Modules\Support\Migrations\SchemaParser;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrationMakeCommand extends GeneratorCommand
{
    protected $name = 'alphacruds:make-migration';

    protected $argumentName = 'model';

    protected $description = 'Creates CRUD Migration in AlphaDev style';

    protected function getTemplateContents(): bool|array|string
    {
        return (new Stub($this->getStub(), [
            'class' => $this->getClass(),
            'table' => $this->getTableName(),
            'fields' => $this->getSchemaParser()->render(),
        ]))->render();
    }

    protected function getDestinationFilePath(): string
    {
        return $this->laravel->databasePath().'/migrations/'.$this->getFileName().'.php';
    }

    private function getStub(): string
    {
        return $this->option('language') ? '/migration-language.stub' : '/migration.stub';
    }

    private function getTableName(): string
    {
        return Str::snake($this->option('language')
            ? Str::singular($this->argument('model'))
            : Str::plural($this->argument('model')));
    }

    public function getSchemaParser(): SchemaParser
    {
        return new SchemaParser($this->argument('fields'));
    }

    private function getFileName(): string
    {
        if ($this->option('language')) {
            return date('Y_m_d_His_') . 'create_' . $this->getTableName() . '_language_table';
        }
        return date('Y_m_d_His_') . 'create_' . $this->getTableName() . '_table';
    }


    protected function getArguments(): array
    {
        return [
            ['model', InputArgument::REQUIRED, 'The migration name will be created.'],
            ['fields', InputArgument::REQUIRED, 'The specified fields table.',],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['language', 'l', InputOption::VALUE_OPTIONAL, 'Create language migration instead.'],
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the request already exists'],
        ];
    }
}
