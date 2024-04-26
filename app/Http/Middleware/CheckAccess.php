<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccess
{
  private function isHttps($app_url): bool
  {
    return strpos($app_url, 'https') !== false;
  }

  private function isHttp($app_url): bool
  {
    return strpos($app_url, 'http') !== false;
  }

  private function checkRole(User $user, string $role): bool
  {
    $roles = Role::getRoles();

    return $user->role()
      ->where('roles.id', $roles[$role])
      ->exists();
  }

  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $email = $request->email;

    $user = User::where('email', $email)->first();

    if (!$user) {
      return redirect()->back()->withErrors(['password' => 'User not found']);
    }

    $app_url = env('APP_URL');

    if (
      $this->isHttp($app_url) && $this->checkRole($user, 'GUEST') ||
      $this->isHttps($app_url) && $this->checkRole($user, 'ADMIN')
    ) {
      abort(403);
    }

    return $next($request);
  }
}
