<?php

namespace DrH\Jenga;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class JengaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-jenga')
            ->hasConfigFile()
            ->hasMigrations(['create_jenga_ipns_table',])
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToRunMigrations();
            });
    }

    public function boot(): JengaServiceProvider|static
    {
        parent::boot();

        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');

        $this->requireHelperScripts();

        return $this;
    }

    private function requireHelperScripts(): void
    {
        $files = glob(__DIR__ . '/Support/*.php');
        foreach ($files as $file) {
            include_once $file;
        }
    }
}
