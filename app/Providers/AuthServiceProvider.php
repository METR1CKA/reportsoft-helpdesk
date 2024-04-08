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
    // - USERS crud
    // - ROLES crud
    // - LOG VIEWER xxxx
    // - TEAMS crud
    // - AREAS crud
    // - REPORT STATUS crud
    // - PROJECTS crud
    // - ENTERPRISES crud
    // - REPORTS crud

    // COORDINATOR
    // - TEAMS crud
    // - AREAS cru
    // - REPORT STATUS cru
    // - PROJECTS crud
    // - ENTERPRISES cru
    // - REPORTS crud

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
