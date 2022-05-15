<?php

namespace Auezov\Permission;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class PermissionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishing();
    }

    public function register()
    {
        $this->callAfterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $this->registerBlade($bladeCompiler);
        });
    }

    protected function registerBlade($bladeCompiler)
    {
        $bladeCompiler->directive('module', function ($arguments) {
            list($module, $guard) = explode(',', $arguments . ',');

            return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasModule({$module}, {$guard})): ?>";
        });
        $bladeCompiler->directive('elsemodule', function ($arguments) {
            list($module, $guard) = explode(',', $arguments . ',');

            return "<?php elseif(auth({$guard})->check() && auth({$guard})->user()->hasModule({$module}, {$guard})): ?>";
        });
        $bladeCompiler->directive('endmodule', function () {
            return '<?php endif; ?>';
        });
    }

    protected function publishing()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/create_permission_tables.php.stub' => $this->getMigrationFileName('create_permission_tables.php'),
        ], 'migrations');
    }

    protected function getMigrationFileName($migrationFileName)
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path . '*_' . $migrationFileName);
            })
            ->push($this->app->databasePath() . "/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}
