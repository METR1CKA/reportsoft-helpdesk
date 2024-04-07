<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  protected $tableName = 'reports';

  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create($this->tableName, function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')
        ->references('id')
        ->on('users')
        ->onUpdate('cascade')
        ->onDelete('cascade');
      $table->foreignId('area_id')
        ->references('id')
        ->on('areas')
        ->onUpdate('cascade')
        ->onDelete('cascade');
      $table->foreignId('enterprise_id')
        ->references('id')
        ->on('enterprises')
        ->onUpdate('cascade')
        ->onDelete('cascade');
      $table->foreignId('project_id')
        ->references('id')
        ->on('projects')
        ->onUpdate('cascade')
        ->onDelete('cascade');
      $table->string('name', 200)->nullable(false);
      $table->longText('description')->nullable(false);
      $table->longText('comments')->nullable(false);
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
