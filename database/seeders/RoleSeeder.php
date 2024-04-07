<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    Role::factory()->createMany([
      [
        'name' => 'ADMIN',
        'description' => 'Administrator for the system',
      ],
      [
        'name' => 'COORDINATOR',
        'description' => 'Coordinator user',
      ],
      [
        'name' => 'GUEST',
        'description' => 'Guest user',
      ],
    ]);
  }
}
