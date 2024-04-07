<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $roles = Role::getRoles();

    $admin = User::create([
      'username' => env('ADMIN_USERNAME'),
      'email' => env('ADMIN_EMAIL'),
      'password' => Hash::make(env('ADMIN_PASSWORD')),
    ]);

    $admin->role()->attach(id: $roles['ADMIN']);

    $admin->authFA()->create([
      'type' => '2FA',
    ]);

    // $admin->authFA()->create([
    //   'type' => '3FA',
    // ]);

    $coordinator = User::create([
      'username' => env('COORDINATOR_USERNAME'),
      'email' => env('COORDINATOR_EMAIL'),
      'password' => Hash::make(env('COORDINATOR_PASSWORD')),
    ]);

    $coordinator->authFA()->create([
      'type' => '2FA',
    ]);

    $coordinator->role()->attach(id: $roles['COORDINATOR']);
  }
}
