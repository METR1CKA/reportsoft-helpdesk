<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthFactor
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $check = Auth::check();

    if (!$check) {
      return redirect()->route('login');
    }

    $roles = Role::getRoles();

    if (Auth::user()->role->first()->id == $roles['ADMIN']) {
      $exists_phone = $request->user()->phone;

      $auth_factor = $request->user()->authFA;

      $all_codes = $auth_factor->map(function ($authFA) {
        return $authFA->code;
      });

      if ($all_codes->contains(null) && !$exists_phone) {
        return redirect()->route('auth-factor.send-code');
      }

      $all_codes_verified = $auth_factor->map(function ($authFA) {
        return $authFA->code_verified;
      });

      if ($all_codes_verified->contains(false)) {
        return redirect()->route('auth-factor.verify-code');
      }
    }

    return $next($request);
  }
}
