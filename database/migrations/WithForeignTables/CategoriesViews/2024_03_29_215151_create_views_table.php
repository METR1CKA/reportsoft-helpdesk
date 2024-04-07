<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  protected $tableName = 'views';

  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create($this->tableName, function (Blueprint $table) {
      $table->id();
      $table->foreignId('category_id')
        ->references('id')
        ->on('categories')
        ->onUpdate('cascade')
        ->onDelete('cascade');
      $table->string('icon', 100)->nullable(false);
      $table->string('path', 200)->nullable(false);
      $table->string('component', 200)->nullable(false);
      $table->string('name', 100)->nullable(false);
      $table->integer('order_index')->nullable(false);
      $table->longText('description')->nullable();
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
