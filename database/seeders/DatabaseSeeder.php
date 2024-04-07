<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    DB::beginTransaction();

    try {
      $this->call([
        RoleSeeder::class,
      ]);

      DB::commit();

      $this->call([
        AreaSeeder::class,
      ]);

      DB::commit();

      $this->call([
        ReportStatusSeeder::class,
      ]);

      DB::commit();

      $this->call([
        UserSeeder::class,
      ]);

      DB::commit();
    } catch (\Exception $e) {
      error_log("\n" . $e->getMessage());

      DB::rollBack();

      Log::error('Error seeding statuses of reports', [
        'STATUS' => 'ERROR',
        'MESSAGE' => $e->getMessage(),
        'LINE' => $e->getLine(),
        'CODE' => $e->getCode(),
      ]);

      return;
    }
  }
}
