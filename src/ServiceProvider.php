<?php

namespace AlphaDevTeam\AlphaCruds;

use AlphaDevTeam\AlphaCruds\Commands\ControllerMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\CreateViewMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\EditViewMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\FormViewMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\IndexViewMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\RequestMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\ShowViewMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\TableViewMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\ViewsMakeCommand;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as ParentProvider;

class ServiceProvider extends ParentProvider
{

    public function boot(): void
    {
        Paginator::useBootstrap();
        $this->registerConfigPublishing();
        $this->registerRoutes();
        $this->registerResources();
        $this->registerAssetPublishing();
        $this->registerCommands();
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

    protected function registerRoutes(): void
    {
        Route::group([
            'namespace' => 'AlphaDevTeam\AlphaCruds\Http\Controllers',
            'middleware' => config('alphacruds.routes.middleware'),
            'prefix' => config('alphacruds.routes.path'),
            'as' => config('alphacruds.routes.route_name_prefix') . '.',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    protected function registerResources(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'alphacruds');
    }

    protected function registerAssetPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/alphacruds'),
        ], 'alphacruds-assets');
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ControllerMakeCommand::class,
                RequestMakeCommand::class,
                EditViewMakeCommand::class,
                CreateViewMakeCommand::class,
                IndexViewMakeCommand::class,
                TableViewMakeCommand::class,
                FormViewMakeCommand::class,
                ShowViewMakeCommand::class,
                ViewsMakeCommand::class,
            ]);
        }
    }
}
