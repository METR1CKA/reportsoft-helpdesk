<?php

namespace App\Providers;

use App\Models\Role;
use Illuminate\Support\Facades\Gate;
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
    // ADMIN
    // - USERS crud x
    // - ROLES crud x
    // - LOG VIEWER xxxx
    // - TEAMS crud x
    // - AREAS crud x
    // - REPORT STATUS crud x
    // - PROJECTS crud x
    // - ENTERPRISES crud
    // - REPORTS crud x

    // COORDINATOR
    // - TEAMS crud x
    // - AREAS cru x
    // - REPORT STATUS cru x
    // - PROJECTS crud x
    // - ENTERPRISES cru
    // - REPORTS crud x

    // GUEST
    // - REPORTS r

    Gate::define('is-admin', function (User $user) {
      return $this->checkRole($user, ['ADMIN']);
    });

    Gate::define('is-admin-coordinator', function (User $user) {
      return $this->checkRole($user, ['ADMIN', 'COORDINATOR']);
    });

    Gate::define('is-admin-coordinator-guest', function (User $user) {
      return $this->checkRole($user, ['ADMIN', 'COORDINATOR', 'GUEST']);
    });
  }

  private function checkRole(User $user, array $roles)
  {
    $roleIds = Role::getRoles();

    foreach ($roles as $role) {
      if ($user->role()->where('roles.id', $roleIds[$role])->exists()) {
        return true;
      }
    }

    return false;
  }
}
