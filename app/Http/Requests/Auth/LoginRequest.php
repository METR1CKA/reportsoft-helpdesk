<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Request para el inicio de sesión.
 */
class LoginRequest extends FormRequest
{
  /**
   * Determina si el usuario está autorizado para hacer esta solicitud.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Obtener las reglas de validación que se aplican a la solicitud.
   *
   * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
   */
  public function rules(): array
  {
    return [
      'email' => ['required', 'string', 'email'],
      'password' => ['required', 'string'],
      'g-recaptcha-response' => ['required', 'captcha'],
    ];
  }

  /**
   * Autenticar la solicitud.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function authenticate(): void
  {
    Log::info('METHOD AUTHENTICATE', [
      'CONTROLLER' => LoginRequest::class,
      'METHOD' => 'authenticate',
    ]);

    $this->ensureIsNotRateLimited();

    $attempt = Auth::attempt($this->only('email', 'password'), $this->boolean('remember'));

    if (!$attempt) {
      Log::alert('NO ATTEMPT', [
        'MESSAGE' => trans('auth.failed')
      ]);

      RateLimiter::hit($this->throttleKey());

      throw ValidationException::withMessages([
        'password' => trans('auth.failed'),
      ]);
    }

    $user = Auth::user();

    if (!$user->active) {
      Auth::logout();

      Log::alert('USER INACTIVE TRYING TO LOGIN', [
        'USER' => $user,
        'MESSAGE' => trans('auth.failed')
      ]);

      throw ValidationException::withMessages([
        'password' => trans('auth.failed'),
      ]);
    }

    RateLimiter::clear($this->throttleKey());
  }

  /**
   * Asegúrarse de que el usuario no esté limitado.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function ensureIsNotRateLimited(): void
  {
    if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
      return;
    }

    event(new Lockout($this));

    $seconds = RateLimiter::availableIn($this->throttleKey());

    throw ValidationException::withMessages([
      'email' => trans('auth.throttle', [
        'seconds' => $seconds,
        'minutes' => ceil($seconds / 60),
      ]),
    ]);
  }

  /**
   * Obtiene la clave de throttle para la solicitud.
   */
  public function throttleKey(): string
  {
    return Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());
  }
}
