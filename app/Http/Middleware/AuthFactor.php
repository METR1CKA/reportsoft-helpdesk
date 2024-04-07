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
    $_2fa = $request->user()->authFA
      ->filter(function ($authFA) {
        return $authFA->type == '2FA';
      })
      ->first();

    if (!$_2fa->code && !$_2fa->code_verified) {
      return redirect()->route('2FA.send-code');
    }

    if ($_2fa->code && !$_2fa->code_verified) {
      return redirect()->route('2FA.verify-code');
    }

    // $roles = Role::getRoles();

    // $user = $request->user();

    // if ($user->role->first()->id == $roles['ADMIN']) {
    //   $_3fa = $user->authFA
    //     ->filter(function ($authFA) {
    //       return $authFA->type == '3FA';
    //     })
    //     ->first();

    //   if (!$_3fa->code_verified) {
    //     return redirect()->route('3FA.verify-code');
    //   }
    // }

    return $next($request);
  }
}
