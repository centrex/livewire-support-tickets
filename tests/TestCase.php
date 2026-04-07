<?php

declare(strict_types = 1);

namespace Centrex\LivewireSupportTickets\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Centrex\LivewireSupportTickets\LivewireSupportTicketsServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Centrex\\LivewireSupportTickets\\Database\\Factories\\' . class_basename($modelName) . 'Factory',
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireSupportTicketsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_livewire-support-tickets_table.php.stub';
        $migration->up();
        */
    }
}
