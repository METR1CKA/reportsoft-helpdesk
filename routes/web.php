<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware([
  'auth',
  'verified',
  'auth-factor'
])->group(function () {
  // Ruta principal
  Route::get('/', function () {
    return redirect()->route('dashboard');
  })
    ->name('/');

  // Ruta de dashboard
  Route::get('/dashboard', function () {
    return view('dashboard');
  })
    ->name('dashboard');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/modules.php';
