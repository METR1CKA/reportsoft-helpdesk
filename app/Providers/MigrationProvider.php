<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MigrationProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  private function getDirectories($path)
  {
    $directories = glob($path . '/*', GLOB_ONLYDIR | GLOB_NOSORT);

    $subDirectories = array_reduce($directories, function ($carry, $directory) {
      return array_merge($carry, $this->getDirectories($directory));
    }, []);

    return array_merge($directories, $subDirectories);
  }

  private function loadMigrations(): void
  {
    $mainPath = database_path('migrations');

    $directories = $this->getDirectories($mainPath);

    $paths = array_merge([$mainPath], $directories);

    $this->loadMigrationsFrom($paths);
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    $this->loadMigrations();
  }
}
