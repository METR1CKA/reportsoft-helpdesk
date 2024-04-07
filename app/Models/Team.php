<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
  use HasFactory;

  /**
   * Los atributos que son asignables en masa.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'active'
  ];

  /**
   * Indica si el modelo debe tener estampas de tiempo.
   *
   * @var bool
   */
  public $timestamps = false;

  /**
   * Obtiene los usuarios asociados al equipo.
   */
  public function users(): BelongsToMany
  {
    return $this->belongsToMany(
      related: User::class,
      table: 'teams_users',
      foreignPivotKey: 'team_id',
      relatedPivotKey: 'user_id',
    );
  }

  /**
   * Obtiene los proyectos asociados al equipo.
   */
  public function projects(): BelongsToMany
  {
    return $this->belongsToMany(
      related: User::class,
      table: 'teams_projects',
      foreignPivotKey: 'team_id',
      relatedPivotKey: 'project_id',
    );
  }
}
