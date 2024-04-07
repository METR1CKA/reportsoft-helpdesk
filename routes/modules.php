<?php

use App\Http\Controllers\Modules\UserController;
use App\Http\Controllers\Modules\RolesController;
use App\Http\Controllers\Modules\AreasController;
use App\Http\Controllers\Modules\ProjectsController;
use App\Http\Controllers\Modules\ReportStatusesController;
use App\Http\Controllers\Modules\TeamsController;
use App\Http\Controllers\Modules\EnterprisesController;
use App\Http\Controllers\Modules\ReportsController;
use Illuminate\Support\Facades\Route;

Route::middleware([
  'auth',
  'verified',
  'auth-factor'
])->group(function () {
  // Password confirmation
  Route::middleware([
    'password.confirm'
  ])->group(function () {
    // Rutas de usuarios
    Route::prefix('users')->group(function () {
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

    // Rutas de roles
    Route::prefix('roles')->group(function () {
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
  });

  Route::prefix('teams')->group(function () {
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

  Route::prefix('areas')->group(function () {
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

  Route::prefix('report/statuses')->group(function () {
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

  Route::prefix('projects')->group(function () {
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

  Route::prefix('enterprises')->group(function () {
    Route::get('/', [EnterprisesController::class, 'index'])
      ->name('enterprises.index');

    Route::get('/search', [EnterprisesController::class, 'show'])
      ->name('enterprises.show');

    Route::get('/create', [EnterprisesController::class, 'create'])
      ->name('enterprises.create');

    Route::post('/create', [EnterprisesController::class, 'store']);

    Route::get('/update/{id}', [EnterprisesController::class, 'edit'])
      ->name('enterprises.update');

    Route::put('/update/{id}', [EnterprisesController::class, 'update']);

    Route::delete('/{id}', [EnterprisesController::class, 'destroy'])
      ->name('enterprises.delete');
  });

  Route::prefix('reports')->group(function () {
    Route::get('/', [ReportsController::class, 'index'])
      ->name('reports.index');

    Route::get('/search', [ReportsController::class, 'show'])
      ->name('reports.show');

    Route::get('/create', [ReportsController::class, 'create'])
      ->name('reports.create');

    Route::post('/create', [ReportsController::class, 'store']);

    Route::get('/update/{id}', [ReportsController::class, 'edit'])
      ->name('reports.update');

    Route::put('/update/{id}', [ReportsController::class, 'update']);

    Route::delete('/{id}', [ReportsController::class, 'destroy'])
      ->name('reports.delete');
  });
});
