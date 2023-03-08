<?php

namespace Aqamarine\AlphaCruds;

use Aqamarine\AlphaCruds\Commands\ApiControllerMakeCommand;
use Aqamarine\AlphaCruds\Commands\ApiRoutesMakeCommand;
use Aqamarine\AlphaCruds\Commands\ApiTestMakeCommand;
use Aqamarine\AlphaCruds\Commands\BaseModelMakeCommand;
use Aqamarine\AlphaCruds\Commands\ControllerMakeCommand;
use Aqamarine\AlphaCruds\Commands\FactoryMakeCommand;
use Aqamarine\AlphaCruds\Commands\MigrationMakeCommand;
use Aqamarine\AlphaCruds\Commands\ModelMakeCommand;
use Aqamarine\AlphaCruds\Commands\RequestMakeCommand;
use Aqamarine\AlphaCruds\Commands\ResourceMakeCommand;
use Aqamarine\AlphaCruds\Commands\RoutesMakeCommand;
use Aqamarine\AlphaCruds\Commands\TestMakeCommand;
use Aqamarine\AlphaCruds\Commands\ViewMakeCommands\CreateViewMakeCommand;
use Aqamarine\AlphaCruds\Commands\ViewMakeCommands\EditViewMakeCommand;
use Aqamarine\AlphaCruds\Commands\ViewMakeCommands\FormViewMakeCommand;
use Aqamarine\AlphaCruds\Commands\ViewMakeCommands\IndexViewMakeCommand;
use Aqamarine\AlphaCruds\Commands\ViewMakeCommands\LanguagesFormViewMakeCommand;
use Aqamarine\AlphaCruds\Commands\ViewMakeCommands\ShowViewMakeCommand;
use Aqamarine\AlphaCruds\Commands\ViewMakeCommands\TableViewMakeCommand;
use Aqamarine\AlphaCruds\Commands\ViewMakeCommands\ViewsMakeCommand;
use Aqamarine\AlphaCruds\Http\Middlewares\OnlyLocalMiddleware;
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
            'namespace' => 'Aqamarine\AlphaCruds\Http\Controllers',
            'middleware' => array_merge(config('alphacruds.routes.middleware'), [OnlyLocalMiddleware::class]),
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
                ResourceMakeCommand::class,
                ApiRoutesMakeCommand::class,
                LanguagesFormViewMakeCommand::class,
                BaseModelMakeCommand::class,
                MigrationMakeCommand::class,
                ApiTestMakeCommand::class,
                FactoryMakeCommand::class,
                TestMakeCommand::class,
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
            ResourceMakeCommand::class,
            ApiRoutesMakeCommand::class,
            LanguagesFormViewMakeCommand::class,
            BaseModelMakeCommand::class,
            MigrationMakeCommand::class,
            ApiTestMakeCommand::class,
            FactoryMakeCommand::class,
            TestMakeCommand::class,
        ]);
    }
}
