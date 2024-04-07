<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
  use HasFactory;

  /**
   * Los atributos que son asignables en masa.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'description',
    'active'
  ];

  /**
   * Obtiene las vistas asociados a la categoria.
   */
  public function views(): HasMany
  {
    return $this->hasMany(
      related: View::class,
      foreignKey: 'category_id',
      localKey: 'id'
    );
  }
}
