<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  protected $tableName = 'users';

  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create($this->tableName, function (Blueprint $table) {
      $table->id();
      $table->string('username', 100)->nullable(false);
      $table->string('email', 200)
        ->unique()
        ->nullable(false);
      $table->timestamp('email_verified_at')->nullable();
      $table->string('phone', 15)->nullable();
      $table->string('password')->nullable(false);
      $table->rememberToken();
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
