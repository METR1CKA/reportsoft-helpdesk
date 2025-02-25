<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterPostRequest;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para registrar usuarios.
 */
class RegisteredUserController extends Controller
{
  /**
   * Muestra la vista de registro.
   *
   * @return \Illuminate\View\View
   */
  public function create(): View
  {
    return view('auth.register');
  }

  /**
   * Crea un nuevo usuario.
   *
   * @param  \App\Http\Requests\RegisterPostRequest  $request
   * @return \Illuminate\Http\RedirectResponse
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(RegisterPostRequest $request): RedirectResponse
  {
    Log::info('REQUEST TO REGISTER', [
      'ACTION' => 'Register',
      'HTTP-VERB' => $request->method(),
      'URL' => $request->url(),
      'IP' => $request->ip(),
      'USER_AGENT' => $request->userAgent(),
      'SESSION' => $request->session()->all(),
      'CONTROLLER' => RegisteredUserController::class,
      'METHOD' => 'store',
    ]);

    $data = $request->validated();

    DB::beginTransaction();

    Log::info('VALIDATION TO REGISTER PASSED', [
      'STATUS' => 'SUCCESS',
      'ACTION' => 'Register',
      'USER' => $request->user(),
    ]);

    try {
      $roles = Role::getRoles();
      $count_users = User::all()->count();

      $role_map = [
        0 => $roles['ADMIN'],
        1 => $roles['COORDINATOR'],
      ];

      $role_id = $role_map[$count_users] ?? $roles['GUEST'];

      $user = User::create([
        'username' => $data['username'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'active' => true,
        'phone' => $data['phone'],
      ]);

      Log::info('USER CREATED', [
        'STATUS' => 'SUCCESS',
        'ACTION' => 'Register',
        'USER' => $user,
      ]);

      DB::commit();

      $user->role()->attach(id: $role_id);

      DB::commit();

      $user->authFA()->create([
        'type' => '2FA'
      ]);

      if ($role_id == $roles['ADMIN']) {
        $user->authFA()->create([
          'type' => '3FA'
        ]);
      }

      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();

      error_log($e->getMessage());

      Log::error('ERROR CREATING USER', [
        'STATUS' => 'ERROR',
        'ACTION' => 'Register',
        'USER' => $request->user(),
        'MESSAGE' => $e->getMessage(),
        'LINE_CODE' => $e->getLine(),
        'FILE' => $e->getFile(),
      ]);

      return redirect()
        ->back()
        ->withErrors([
          'password_confirmation' => 'There was an error creating the user.',
        ]);
    }

    return redirect()->route('login')
      ->with('status', __('User created successfully.'));
  }
}
