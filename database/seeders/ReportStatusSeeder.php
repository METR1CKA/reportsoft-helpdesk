<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReportStatus;

class ReportStatusSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    ReportStatus::factory()->createMany([
      [
        'name' => 'PENDING',
        'description' => 'Pending reports to fix or review',
      ],
      [
        'name' => 'IN PROGRESS',
        'description' => 'Reports being fixed by the dev',
      ],
      [
        'name' => 'IN REVIEW',
        'description' => 'Reports being reviewed and testing by the team lead and QA',
      ],
      [
        'name' => 'DONE',
        'description' => 'Reports fixed, reviewed, tested and approved',
      ],
      [
        'name' => 'REJECTED',
        'description' => 'Reports rejected by the team lead or QA due to issues',
      ],
      [
        'name' => 'REOPENED',
        'description' => 'Reports reopened by the team lead or QA',
      ]
    ]);
  }
}
