<?php

namespace App\Providers;

// use App\Models\Role;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\UserPolicy;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The model to policy mappings for the application.
   *
   * @var array<class-string, class-string>
   */
  protected $policies = [
    User::class => UserPolicy::class,
  ];

  /**
   * Register any authentication / authorization services.
   */
  public function boot(): void
  {
    // $rv = Role::with('views')->get();

    // foreach ($rv as $role) {
    //   foreach ($role->views as $view) {
    //     Gate::define($view->name, function (User $user) use ($role) {
    //       return $user->role->id == $role->id;
    //     });
    //   }
    // }

    // Gate::define('', function (User $user) {
    //   $roles = Role::getRoles();

    //   return $user->role->id == $roles['ADMIN'];
    // });
  }
}
