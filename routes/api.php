<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('v1/check-net', function () {
  return response()->json(
    data: [
      'message' => 'API is working!',
      'data' => null,
    ],
    status: 200
  );
});

Route::middleware([
  'api'
])
  ->prefix('v1/auth')
  ->group(function () {
    Route::post('login', [ApiAuthController::class, 'login']);

    Route::middleware([
      'auth:sanctum'
    ])
      ->group(function () {
        Route::post('logout', [ApiAuthController::class, 'logout']);
        Route::post('verify-code', [ApiAuthController::class, 'verifyCode']);
      });
  });
