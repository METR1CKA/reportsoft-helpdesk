<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  protected $tableCache = 'cache';
  protected $tableCacheLocks = 'cache_locks';

  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create($this->tableCache, function (Blueprint $table) {
      $table->string('key')->primary();
      $table->mediumText('value');
      $table->integer('expiration');
    });

    Schema::create($this->tableCacheLocks, function (Blueprint $table) {
      $table->string('key')->primary();
      $table->string('owner');
      $table->integer('expiration');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists($this->tableCache);
    Schema::dropIfExists($this->tableCacheLocks);
  }
};
