<?php

use App\Http\Controllers\Modules\UserController;
use App\Http\Controllers\Modules\RolesController;
use App\Http\Controllers\Modules\AreasController;
use App\Http\Controllers\Modules\ProjectsController;
use App\Http\Controllers\Modules\ReportStatusesController;
use App\Http\Controllers\Modules\TeamsController;
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

  Route::middleware([
    // 'password.confirm'
  ])->prefix('roles')->group(function () {
    Route::get('/', [RolesController::class, 'index'])
      ->name('roles.index');

    Route::get('/search', [RolesController::class, 'show'])
      ->name('roles.show');

    Route::get('/create', [RolesController::class, 'create'])
      ->name('roles.create');

    Route::post('/create', [RolesController::class, 'store']);

    Route::get('/update/{id}', [RolesController::class, 'edit'])
      ->name('roles.update');

    Route::put('/update/{id}', [RolesController::class, 'update']);

    Route::delete('/{id}', [RolesController::class, 'destroy'])
      ->name('roles.delete');
  });

  Route::middleware([
    // 'password.confirm'
  ])->prefix('teams')->group(function () {
    Route::get('/', [TeamsController::class, 'index'])
      ->name('teams.index');

    Route::get('/search', [TeamsController::class, 'show'])
      ->name('teams.show');

    Route::get('/create', [TeamsController::class, 'create'])
      ->name('teams.create');

    Route::post('/create', [TeamsController::class, 'store']);

    Route::get('/update/{id}', [TeamsController::class, 'edit'])
      ->name('teams.update');

    Route::put('/update/{id}', [TeamsController::class, 'update']);

    Route::delete('/{id}', [TeamsController::class, 'destroy'])
      ->name('teams.delete');
  });

  Route::middleware([
    // 'password.confirm'
  ])->prefix('areas')->group(function () {
    Route::get('/', [AreasController::class, 'index'])
      ->name('areas.index');

    Route::get('/search', [AreasController::class, 'show'])
      ->name('areas.show');

    Route::get('/create', [AreasController::class, 'create'])
      ->name('areas.create');

    Route::post('/create', [AreasController::class, 'store']);

    Route::get('/update/{id}', [AreasController::class, 'edit'])
      ->name('areas.update');

    Route::put('/update/{id}', [AreasController::class, 'update']);

    Route::delete('/{id}', [AreasController::class, 'destroy'])
      ->name('areas.delete');
  });

  Route::middleware([
    // 'password.confirm'
  ])->prefix('report/statuses')->group(function () {
    Route::get('/', [ReportStatusesController::class, 'index'])
      ->name('report_statuses.index');

    Route::get('/search', [ReportStatusesController::class, 'show'])
      ->name('report_statuses.show');

    Route::get('/create', [ReportStatusesController::class, 'create'])
      ->name('report_statuses.create');

    Route::post('/create', [ReportStatusesController::class, 'store']);

    Route::get('/update/{id}', [ReportStatusesController::class, 'edit'])
      ->name('report_statuses.update');

    Route::put('/update/{id}', [ReportStatusesController::class, 'update']);

    Route::delete('/{id}', [ReportStatusesController::class, 'destroy'])
      ->name('report_statuses.delete');
  });

  Route::middleware([
    // 'password.confirm'
  ])->prefix('projects')->group(function () {
    Route::get('/', [ProjectsController::class, 'index'])
      ->name('projects.index');

    Route::get('/search', [ProjectsController::class, 'show'])
      ->name('projects.show');

    Route::get('/create', [ProjectsController::class, 'create'])
      ->name('projects.create');

    Route::post('/create', [ProjectsController::class, 'store']);

    Route::get('/update/{id}', [ProjectsController::class, 'edit'])
      ->name('projects.update');

    Route::put('/update/{id}', [ProjectsController::class, 'update']);

    Route::delete('/{id}', [ProjectsController::class, 'destroy'])
      ->name('projects.delete');
  });
});
