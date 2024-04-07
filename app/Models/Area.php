<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
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
   * Indica si el modelo debe tener estampas de tiempo.
   *
   * @var bool
   */
  public $timestamps = false;

  /**
   * Obtiene los usuarios asociados al area.
   */
  public function users(): BelongsToMany
  {
    return $this->belongsToMany(
      related: User::class,
      table: 'areas_users',
      foreignPivotKey: 'area_id',
      relatedPivotKey: 'user_id',
    );
  }

  /**
   * Obtiene los reportes asociados al area.
   */
  public function reports(): HasMany
  {
    return $this->hasMany(
      related: Report::class,
      foreignKey: 'area_id',
      localKey: 'id'
    );
  }
}
