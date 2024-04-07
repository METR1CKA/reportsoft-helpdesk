<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
    $user = $request->user();

    $codes_null = $user->authFA()
      ->whereNull('code')
      ->count();

    $codes_not_verified = $user->authFA()
      ->where('code_verified', false)
      ->count();

    if ($codes_null > 0 && $codes_not_verified > 0) {
      return redirect()->route('auth-factor.send-code');
    }

    if ($codes_null == 0 && $codes_not_verified > 0) {
      return redirect()->route('auth-factor.verify-code');
    }

    return $next($request);
  }
}
