<?php

namespace AlphaDevTeam\AlphaCruds;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider as ParentProvider;

class ServiceProvider extends ParentProvider
{

    public function boot(): void
    {
        Paginator::useBootstrap();
        $this->registerConfigPublishing();
    }

    public function register(): void
    {
        $this->configure();
    }

    protected function registerConfigPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../config/alphacruds.php' => config_path('alphacruds.php'),
        ], 'alphanews-config');
    }

    protected function configure(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/alphacruds.php', 'alphacruds');
    }

}
