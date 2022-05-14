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
        $timestamp = date('Y_m_d_His');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . "/../database/migrations/create_permission_tables.stub" => $this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . $timestamp . 'create_permission_tables',
            ], 'migrations');
        }
    }
}
