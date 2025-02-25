<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  protected $tableName = 'teams_projects';

  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create($this->tableName, function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')
        ->references('id')
        ->on('projects')
        ->onUpdate('cascade')
        ->onDelete('cascade');
      $table->foreignId('team_id')
        ->references('id')
        ->on('teams')
        ->onUpdate('cascade')
        ->onDelete('cascade');
      $table->boolean('active')
        ->nullable(false)
        ->default(true);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists($this->tableName);
  }
};
