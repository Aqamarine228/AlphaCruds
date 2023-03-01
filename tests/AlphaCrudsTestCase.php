<?php

namespace AlphaDevTeam\AlphaCruds\Tests;

use AlphaDevTeam\AlphaCruds\ServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase;

class AlphaCrudsTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setParameters(string|int $key, mixed $value): void
    {
        $this->routeParameters[$key] = $value;
    }

    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $this->setConfig($app);
    }

    private function setConfig($app): void
    {
        app()->detectEnvironment(function () {
            return 'local';
        });
        $app['config']->set('app.key', 'base64:nXRgrnH481Fce7gJME6MQbIfBTqXeBYbz6DHwP7m2gQ=');
        $app['config']->set('app.env', 'local');
        $app['config']->set('cache.default', 'array');
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('alphacruds.routes.middleware', ['web']);
    }
}
