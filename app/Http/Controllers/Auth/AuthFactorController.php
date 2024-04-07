<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
   * Enviar el código de verificación a través de SMS.
   *
   * @param  string  $phone
   * @param  string  $code
   *
   * @return bool
   */
  private function sendMailCode($user, $code)
  {
    try {
      $user->notify(new SendCodeAuthFactor($user, $code));

      Log::info('SEND CODE', [
        'STATUS' => 'SUCCESS',
        'ACTION' => 'Send code',
        'CONTROLLER' => AuthFactorController::class,
        'METHOD' => 'sendMailCode',
        'CODE' => $code,
      ]);
    } catch (\Exception $e) {
      Log::error('SEND CODE WITH ERROR', [
        'STATUS' => 'ERROR',
        'ACTION' => 'Send code',
        'ERROR' => $e->getMessage(),
        'LINE_CODE' => $e->getLine(),
        'FILE' => $e->getFile(),
      ]);

      return false;
    }

    return true;
  }

  /**
   * Muestra la vista para establecer el número de teléfono.
   * @return RedirectResponse|View
   */
  public function viewSendCode2FA(): RedirectResponse|View
  {
    $roles = Role::getRoles();

    $role_id = Auth::user()->role->first()->id;

    return $role_id == $roles['ADMIN']
      ? view('auth.factor.send-code')
      : redirect()->intended(RouteServiceProvider::HOME);
  }

  /**
   * Establecer el número de teléfono y enviar el código de verificación a través de SMS.
   *
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function sendCode2FA(Request $request): RedirectResponse
  {
    // Generar un código de verificación
    $code = GenerateCodes::generateNumberCode();

    // Actualizar el código de verificación en la base de datos
    $request->user()->authFA()
      ->where('type', '2FA')
      ->update([
        'code' => Hash::make($code),
      ]);

    // Enviar el código a través de EMAIL usando MAIL
    $send = $this->sendMailCode($request->user(), $code);

    if (!$send) {
      return redirect()
        ->back()
        ->with('status', 'verification-link-sent-error');
    }

    // Redirigir al usuario
    return redirect()
      ->back()
      ->with('status', 'verification-link-sent');
  }

  /**
   * Muestra la vista para verificar el código.
   *
   * @return RedirectResponse|View
   */
  public function viewVerifyCode2FA(): RedirectResponse|View
  {
    $roles = Role::getRoles();

    $code_verified = Auth::user()->authFA()
      ->where('type', '2FA')
      ->first()
      ->code_verified;

    $role_id = Auth::user()->role()->first()->id;

    return !$code_verified && $role_id == $roles['ADMIN']
      ? view('auth.factor.verify-2fa')
      : redirect()->intended(RouteServiceProvider::HOME);
  }

  /**
   * Validar el código de verificación.
   *
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function verifyCode2FA(Request $request): RedirectResponse
  {
    // Validar el código de verificación
    $request->validate([
      'code' => ['required', 'string', 'size:6'],
      'g-recaptcha-response' => ['required', 'captcha'],
    ]);

    // Verificar si el código es correcto
    $code_user = $request->user()->authFA()
      ->where('type', '2FA')
      ->first()
      ->code;

    $is_valid = Hash::check($request->code, $code_user);

    if (!$is_valid) {
      // El código es incorrecto, volver a mostrar el formulario de verificación
      return redirect()
        ->back()
        ->withErrors(['code' => 'The 2FA code is incorrect.']);
    }

    // Marcar el código como verificado
    $request->user()->authFA()
      ->where('type', '2FA')
      ->update([
        'code_verified' => $is_valid,
      ]);

    // Autenticar al usuario si no es administrador
    $roles = Role::getRoles();

    if (Auth::user()->role()->first()->id != $roles['ADMIN']) {
      Auth::login($request->user());
    }

    // Redirigir al usuario
    return redirect()->intended(RouteServiceProvider::HOME);
  }

  /**
   * Muestra la vista para verificar el código.
   *
   * @return RedirectResponse|View
   */
  public function viewVerifyCode3FA(): RedirectResponse|View
  {
    $roles = Role::getRoles();

    $code_verified = Auth::user()->authFA()
      ->where('type', '3FA')
      ->first()
      ->code_verified;

    $role_id = Auth::user()->role()->first()->id;

    return !$code_verified && $role_id == $roles['ADMIN']
      ? view('auth.factor.verify-2fa')
      : redirect()->intended(RouteServiceProvider::HOME);
  }

  /**
   * Validar el código de verificación.
   *
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function verifyCode3FA(Request $request): RedirectResponse
  {
    // Validar el código de verificación
    $request->validate([
      'code' => ['required', 'string', 'size:6'],
      'g-recaptcha-response' => ['required', 'captcha'],
    ]);

    // Verificar si el código es correcto
    $code_user = $request->user()->authFA()
      ->where('type', '3FA')
      ->first()
      ->code;

    $is_valid = Hash::check($request->code, $code_user);

    if (!$is_valid) {
      // El código es incorrecto, volver a mostrar el formulario de verificación
      return redirect()
        ->back()
        ->withErrors(['code' => 'The 3FA code is incorrect.']);
    }

    // Marcar el código como verificado
    $request->user()->authFA()
      ->where('type', '3FA')
      ->update([
        'code_verified' => $is_valid,
      ]);

    // El código es correcto, autenticar al usuario
    Auth::login($request->user());

    // Redirigir al usuario
    return redirect()->intended(RouteServiceProvider::HOME);
  }
}
