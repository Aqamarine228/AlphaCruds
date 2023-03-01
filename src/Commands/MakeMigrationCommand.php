<?php

namespace AlphaDevTeam\AlphaCruds\Commands;

use AlphaDevTeam\AlphaCruds\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Support\Migrations\SchemaParser;
use Symfony\Component\Console\Input\InputArgument;

class MakeMigrationCommand extends GeneratorCommand
{
    protected $name = 'alphacruds:make-migration';

    protected $argumentName = 'model';

    protected $description = 'Creates CRUD Migration in AlphaDev style';

    protected function getTemplateContents(): bool|array|string
    {
        return (new Stub('/migration.stub', [
            'class' => $this->getClass(),
            'table' => $this->getTableName(),
            'fields' => $this->getSchemaParser()->render(),
        ]))->render();
    }

    protected function getDestinationFilePath(): string
    {
        return $this->laravel->databasePath().'/migrations/'.$this->getFileName().'.php';
    }

    private function getTableName(): string
    {
        return Str::snake(Str::plural($this->argument('model')));
    }

    public function getSchemaParser(): SchemaParser
    {
        return new SchemaParser($this->argument('fields'));
    }

    private function getFileName(): string
    {
        return date('Y_m_d_His_') . 'create_' . $this->getTableName() . '_table';
    }


    protected function getArguments(): array
    {
        return [
            ['model', InputArgument::REQUIRED, 'The migration name will be created.'],
            ['fields', InputArgument::REQUIRED, 'The specified fields table.',],

        ];
    }
}
