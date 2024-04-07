<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
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
   * Obtiene los equipos asociados al proyecto.
   */
  public function teams(): BelongsToMany
  {
    return $this->belongsToMany(
      related: Team::class,
      table: 'teams_projects',
      foreignPivotKey: 'project_id',
      relatedPivotKey: 'team_id',
    );
  }

  /**
   * Obtiene las empresas asociadas al proyecto.
   */
  public function enterprises(): BelongsToMany
  {
    return $this->belongsToMany(
      related: User::class,
      table: 'projects_enterprises',
      foreignPivotKey: 'project_id',
      relatedPivotKey: 'enterprise_id',
    );
  }

  /**
   * Obtiene los reportes asociados al proyecto.
   */
  public function reports(): HasMany
  {
    return $this->hasMany(
      related: Report::class,
      foreignKey: 'project_id',
      localKey: 'id'
    );
  }
}
