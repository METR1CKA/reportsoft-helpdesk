<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  protected $tableName = 'enterprises';

  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create($this->tableName, function (Blueprint $table) {
      $table->id();
      $table->string('contact_name', 100)->nullable(false);
      $table->string('contact_phone', 15)->nullable(false);
      $table->string('contact_email', 200)->nullable(false);
      $table->string('legal_id', 200)->nullable(false);
      $table->string('legal_name', 200)->nullable(false);
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
