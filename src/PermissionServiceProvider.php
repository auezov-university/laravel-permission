<?php

namespace Auezov\Permission;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->offerPublishing();
    }

    protected function offerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/create_permission_tables.php.stub' => $this->getMigrationFileName('create_permission_tables.php'),
        ], 'migrations');
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @return string
     */
    protected function getMigrationFileName($migrationFileName)
    {
        $timestamp = date('Y_m_d_His');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => $this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . $timestamp . $migrationFileName,
            ], 'migrations');
        }
    }
}
