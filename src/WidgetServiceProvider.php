<?php

namespace PSV\Widgets;

use Illuminate\Support\ServiceProvider;
use App;
use Blade;


class WidgetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/' => config_path() . '/']);

            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'redirect-migrations');
            $this->commands([
                Console\CreateCommand::class,
                Console\UpdateCommand::class,
                Console\RemoveCommand::class,
            ]);
        }
    }
}