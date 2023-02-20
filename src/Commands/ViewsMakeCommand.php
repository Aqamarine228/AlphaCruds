<?php

namespace AlphaDevTeam\AlphaCruds\Commands;

use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Traits\ModuleCommandTrait;

class ViewsMakeCommand extends GeneratorCommand
{

    use ModuleCommandTrait;

    protected $name = 'alphacruds:make-views';

    protected $argumentName = 'model';

    protected $description = 'Creates CRUD views in AlphaDev style';

    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());
    }

    protected function getDestinationFilePath()
    {
        // TODO: Implement getDestinationFilePath() method.
    }


}
