<?php

namespace SoapBox\SerializedPayloads\Tests;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as Base;
use SoapBox\SerializedPayloads\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends Base
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'SoapBox\\SerializedPayloads\\Factories\\' . Str::afterLast($modelName, '\\') . 'Factory';
        });

        parent::setUp();
        $this->refreshDatabase();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }
}
