<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

class SendCodeFAController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $user = $request->user();

    $code2FA_is_null = $user->authFA()
      ->where('type', '2FA')
      ->whereNull('code')
      ->exists();

    if (!$code2FA_is_null) {
      return redirect()->intended(RouteServiceProvider::HOME);
    }

    $status = AuthFactorController::sendCodeFA(
      user: $request->user(),
      len: 12,
    );

    if (!$status['status']) {
      return redirect()
        ->route('auth-factor.verify-code')
        ->with('mail-error', $status['error'])
        ->with('status', 'verification-link-sent-error');
    }

    return redirect()
      ->route('auth-factor.verify-code')
      ->with('status', 'verification-link-sent');
  }
}
