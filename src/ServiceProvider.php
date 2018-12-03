<?php

namespace SoapBox\SerializedPayloads;

use SoapBox\SerializedPayloads\Commands\Prune;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([Prune::class]);
        }
    }
}
