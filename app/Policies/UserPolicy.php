<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class UserPolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function isValidRole(User $user): bool
  {
    $roles = Role::getRoles();

    return $user->role()
      ->where('roles.id', $roles['ADMIN'])
      ->exists();
  }
}
