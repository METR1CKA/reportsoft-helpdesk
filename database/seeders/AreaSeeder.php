<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Area::factory()->createMany([
      [
        'name' => 'SERVERS',
        'description' => 'Servers, Infrastructure, Cloud, Hosting, etc.',
      ],
      [
        'name' => 'DATABASE',
        'description' => 'Database administration',
      ],
      [
        'name' => 'BACKEND',
        'description' => 'Backend && API',
      ],
      [
        'name' => 'FRONTEND',
        'description' => 'Frontend',
      ],
      [
        'name' => 'DESIGN',
        'description' => 'UI/UX',
      ],
      [
        'name' => 'iOS',
        'description' => 'Mobile iOS',
      ],
      [
        'name' => 'ANDROID',
        'description' => 'Mobile Android',
      ],
    ]);
  }
}
