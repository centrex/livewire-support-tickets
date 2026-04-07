<?php

declare(strict_types = 1);

namespace Centrex\LivewireSupportTickets;

use Illuminate\Support\ServiceProvider;

class LivewireSupportTicketsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'livewire-support-tickets');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-support-tickets');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('livewire-support-tickets.php'),
            ], 'livewire-support-tickets-config');

            // Publishing the migrations.
            /*$this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'livewire-support-tickets-migrations');*/

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/livewire-support-tickets'),
            ], 'livewire-support-tickets-views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/livewire-support-tickets'),
            ], 'livewire-support-tickets-assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/livewire-support-tickets'),
            ], 'livewire-support-tickets-lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'livewire-support-tickets');

        // Register the main class to use with the facade
        $this->app->singleton('livewire-support-tickets', function () {
            return new LivewireSupportTickets();
        });
    }
}
