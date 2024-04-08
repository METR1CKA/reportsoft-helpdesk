<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\GenerateCodes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
  /**
   * Login a user.
   */
  public function login(Request $request)
  {
    // Validar el json
    try {
      $credentials = $request->validate(
        rules: [
          'email' => ['required', 'email', 'string'],
          'password' => ['required', 'string'],
        ],
        messages: [
          'required' => 'The :attribute field is required',
          'email' => 'The :attribute field must be a valid email address',
          'string' => 'The :attribute field must be a string',
        ]
      );
    } catch (\Exception $err) {
      return response()
        ->json(
          data: [
            'message' => 'Validation error',
            'data' => [
              'errors' => $err->getMessage(),
            ],
          ],
          status: 400
        );
    }

    // Verificar al usuario
    $user = User::where('email', $request->email)->first();

    $roles = Role::getRoles();

    $is_admin = $user->role()->where('roles.id', $roles['ADMIN'])->exists();

    if (!$user || !$user->active || !$is_admin) {
      return response()->json(
        data: [
          'message' => 'Invalid credentials',
          'data' => null,
        ],
        status: 401
      );
    }

    // Verificar credenciales
    $attempt = Auth::attempt(
      credentials: $credentials,
      remember: $request->boolean('remember')
    );

    if (!$attempt) {
      return response()->json(
        data: [
          'message' => 'Invalid credentials',
          'data' => null,
        ],
        status: 401
      );
    }

    // Generar y retornar token
    $token = Auth::user()->createToken(
      name: 'token',
      expiresAt: now()->addMinutes(30)
    );

    return response()
      ->json(
        data: [
          'message' => 'Login successful',
          'data' => [
            'type' => 'Bearer',
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at->format('Y-m-d H:i:s'),
          ],
        ],
        status: 200
      );
  }

  /**
   * Logout a user.
   */
  public function logout(Request $request)
  {
    // Borrar tokens
    Auth::user()->tokens()->delete();

    return response()
      ->json(
        data: [
          'message' => 'Logout successful',
          'data' => null,
        ],
        status: 200
      );
  }

  /**
   * Verify 2FA code and send 3FA code.
   */
  public function verifyCode(Request $request)
  {
    // Validar usuario
    $user = $request->user();

    $roles = Role::getRoles();

    $is_admin = $user->role()->where('roles.id', $roles['ADMIN'])->exists();

    if (!$is_admin) {
      return response()->json(
        data: [
          'message' => 'Not permitted',
          'data' => null,
        ],
        status: 403
      );
    }

    // Validar el codigo
    try {
      $request->validate(
        rules: [
          'code2FA' => ['required', 'string', 'size:12'],
        ],
        messages: [
          'required' => 'The :attribute field is required',
          'string' => 'The :attribute field must be a string',
          'size' => 'The :attribute field must be 6 characters',
        ]
      );
    } catch (\Exception $err) {
      return response()
        ->json(
          data: [
            'message' => 'Validation error',
            'data' => [
              'errors' => $err->getMessage(),
            ],
          ],
          status: 400
        );
    }

    // Verificar 2FA code
    $current_code2FA = $request->code2FA;

    $code2FA_hashed = $user->authFA()
      ->where('type', '2FA')
      ->first()
      ->code;

    $is_valid = Hash::check($current_code2FA, $code2FA_hashed);

    if (!$is_valid) {
      return response()
        ->json(
          data: [
            'message' => 'Invalid code',
            'data' => null
          ],
          status: 400
        );
    }

    // Actualizar codigo
    $user->authFA()
      ->where('type', '2FA')
      ->update([
        'code_verified' => $is_valid,
      ]);

    // Generar el nuevo codigo
    $code3FA = GenerateCodes::generateNumberCode(12);

    $user->authFA()
      ->where('type', '3FA')
      ->update([
        'code' => Hash::make($code3FA)
      ]);

    return response()
      ->json(
        data: [
          'message' => 'Code verified',
          'data' => [
            'code3FA' => $code3FA
          ],
        ],
        status: 200
      );
  }
}
