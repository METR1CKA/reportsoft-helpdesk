<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminViewWelcome
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $roles = Role::getRoles();

    $is_admin = Auth::user()->role()
      ->where('roles.id', $roles['ADMIN'])
      ->exists();

    if (!$is_admin) {
      return redirect()->route('dashboard');
    }

    return $next($request);
  }
}
