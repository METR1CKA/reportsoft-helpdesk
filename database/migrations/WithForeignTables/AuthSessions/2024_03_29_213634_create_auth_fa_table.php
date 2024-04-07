<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  protected $tableName = 'auth_fa';

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
        ->onDelete('cascade')
        ->index();
      $table->string('type', 5)->nullable();
      $table->string('code', 200)->nullable();
      $table->boolean('code_verified')
        ->nullable(false)
        ->default(false);
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
