<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enterprise extends Model
{
  use HasFactory;

  /**
   * Los atributos que son asignables en masa.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'contact_name',
    'contact_phone',
    'contact_email',
    'legal_id',
    'legal_name',
    'active'
  ];

  /**
   * Obtiene los proyectos asociados a la empresa.
   */
  public function projects(): BelongsToMany
  {
    return $this->belongsToMany(
      related: Project::class,
      table: 'projects_enterprises',
      foreignPivotKey: 'enterprise_id',
      relatedPivotKey: 'project_id',
    );
  }

  /**
   * Obtiene los reportes asociados a la empresa.
   */
  public function reports(): HasMany
  {
    return $this->hasMany(
      related: Report::class,
      foreignKey: 'enterprise_id',
      localKey: 'id'
    );
  }
}
