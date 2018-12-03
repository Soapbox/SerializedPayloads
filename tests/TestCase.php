<?php

namespace SoapBox\SerializedPayloads\Tests;

use Orchestra\Testbench\TestCase as Base;
use SoapBox\SerializedPayloads\ServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends Base
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        $this->refreshDatabase();
        $this->withFactories(__DIR__ . '/factories');
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
