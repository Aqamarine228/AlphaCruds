<?php

namespace AlphaDevTeam\AlphaCruds\Commands;

use Illuminate\Console\GeneratorCommand;

class BaseModelMakeCommand extends GeneratorCommand
{

    protected $name = 'alphacruds:make-base-model';

    protected static $defaultName = 'alphacruds:make-base-model';

    protected $type = 'Model';

    protected function getStub(): string
    {
        return $this->resolveStubPath('/base-model.stub');
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return is_dir(app_path('Models')) ? $rootNamespace.'\\Models' : $rootNamespace;
    }

    protected function resolveStubPath($stub): string
    {
        return __DIR__ . '/stubs' . $stub;
    }
}
