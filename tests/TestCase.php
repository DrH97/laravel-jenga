<?php

namespace DrH\Jenga\Tests;

use DrH\Jenga\JengaServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Event;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    /**
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [JengaServiceProvider::class];
    }

    public function getEnvironmentSetUp($app): void
    {
        $migration = include __DIR__ . '/../database/migrations/create_jenga_ipns_table.php.stub';
        $migration->up();

        $migration = include __DIR__ . '/../database/migrations/create_jenga_bill_ipns_table.php.stub';
        $migration->up();
    }
}
