<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware([
  'auth',
  'verified',
  'auth-factor'
])->group(function () {
  // Rutas de usuarios
  Route::middleware([
    // 'password.confirm'
  ])->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])
      ->name('users.index');

    Route::get('/search', [UserController::class, 'show'])
      ->name('users.show');

    Route::get('/create', [UserController::class, 'create'])
      ->name('users.create');

    Route::post('/create', [UserController::class, 'store']);

    Route::get('/update/{id}', [UserController::class, 'edit'])
      ->name('users.update');

    Route::put('/update/{id}', [UserController::class, 'update']);

    Route::delete('/{id}', [UserController::class, 'destroy'])
      ->name('users.delete');
  });
});
