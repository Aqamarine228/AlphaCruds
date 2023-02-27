<?php

namespace AlphaDevTeam\AlphaCruds;

use AlphaDevTeam\AlphaCruds\Commands\ApiControllerMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\ControllerMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\ModelMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\RequestMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\RoutesMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands\CreateViewMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands\EditViewMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands\FormViewMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands\IndexViewMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands\ShowViewMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands\TableViewMakeCommand;
use AlphaDevTeam\AlphaCruds\Commands\ViewMakeCommands\ViewsMakeCommand;
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
        ], 'alphacruds-config');
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
                RoutesMakeCommand::class,
                ModelMakeCommand::class,
                ApiControllerMakeCommand::class,
            ]);
        }

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
            RoutesMakeCommand::class,
            ModelMakeCommand::class,
            ApiControllerMakeCommand::class,
        ]);
    }
}
