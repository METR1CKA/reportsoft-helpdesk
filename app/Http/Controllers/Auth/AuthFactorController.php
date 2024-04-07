<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Notifications\SendCodeAuthFactor;
use App\Services\GenerateCodes;

class AuthFactorController extends Controller
{
  /**
   * Muestra la vista para verificar el código.
   *
   * @return RedirectResponse|View
   */
  public function viewVerifyCodeFA(): RedirectResponse|View
  {
    return view('auth.verify-auth-factor-code');
  }

  /**
   * Validar el código de verificación.
   *
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function verifyCodeFA(Request $request): RedirectResponse
  {
    // Obtener al usuario autenticado
    $roles = Role::getRoles();

    $user = $request->user();

    $is_admin = $user->role()
      ->where('roles.id', $roles['ADMIN'])
      ->exists();

    $code_is_null = $user->authFA()
      ->where('type', '2FA')
      ->whereNull('code')
      ->exists();

    // Validar el código de verificación
    $request->validate([
      'code' => [
        'required',
        'string',
        'size:' . ($is_admin && !$code_is_null ? 24 : 12)
      ],
      'g-recaptcha-response' => ['required', 'captcha'],
    ]);

    $type = $is_admin ? '3FA' : '2FA';

    if ($is_admin) {
      $check2FA = $user->authFA()
        ->where('type', '2FA')
        ->first()
        ->code_verified;

      if (!$check2FA) {
        return redirect()
          ->back()
          ->withErrors(['code' => 'The 2FA code is not verified, please first verify in mobile app.']);
      }
    }

    // Verificar si el código es correcto
    $code_user = $user->authFA()
      ->where('type', $type)
      ->first()
      ->code;

    $is_valid = Hash::check($request->code, $code_user);

    if (!$is_valid) {
      // El código es incorrecto, volver a mostrar el formulario de verificación
      return redirect()
        ->back()
        ->withErrors(['code' => 'The auth factor code is incorrect.']);
    }

    // Marcar el código como verificado
    $user->authFA()
      ->where('type', $type)
      ->update([
        'code_verified' => $is_valid,
      ]);

    // Autenticar al usuario si no es administrador
    Auth::login($user);

    // Redirigir al usuario
    return redirect()->intended(RouteServiceProvider::HOME);
  }

  /**
   * Envia el codigo de verificación.
   *
   * @param  User  $user
   *
   * @return array<bool|string>
   */
  public static function sendCodeFA(User $user, int $len)
  {
    // Generar un código de verificación
    $code = GenerateCodes::generateNumberCode($len);

    // Actualizar el código de verificación en la base de datos
    $user->authFA()
      ->where('type', '2FA')
      ->update([
        'code' => Hash::make($code),
      ]);

    try {
      $user->notify(new SendCodeAuthFactor($user->username, $code));

      Log::info('SEND CODE', [
        'STATUS' => 'SUCCESS',
        'ACTION' => 'Send code',
        'CONTROLLER' => AuthFactorController::class,
        'METHOD' => 'sendMailCode',
        'CODE' => $code,
      ]);
    } catch (\Exception $err) {
      Log::error('SEND CODE WITH ERROR', [
        'STATUS' => 'ERROR',
        'ACTION' => 'Send code',
        'ERROR' => $err->getMessage(),
        'LINE_CODE' => $err->getLine(),
        'FILE' => $err->getFile(),
      ]);

      return [
        'status' => false,
        'error' => $err->getMessage()
      ];
    }

    return [
      'status' => true,
    ];
  }

  /**
   * Envia el codigo de verificación.
   *
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function resendCodeFA(Request $request)
  {
    $roles = Role::getRoles();

    $user = $request->user();

    $send = $this->sendCodeFA(
      user: $request->user(),
      len: 12,
    );

    if (!$send['status']) {
      return redirect()
        ->back()
        ->with('mail-error', $send['err'])
        ->with('status', 'verification-link-sent-error');
    }

    // Redirigir al usuario
    return redirect()
      ->back()
      ->with('status', 'verification-link-sent');
  }
}
