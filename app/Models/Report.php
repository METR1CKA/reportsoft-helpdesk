<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
  use HasFactory;

  /**
   * Los atributos que son asignables en masa.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'user_id',
    'area_id',
    'report_status_id',
    'enterprise_id',
    'project_id',
    'name',
    'description',
    'comments',
    'active'
  ];

  /**
   * Obtiene el usuario asociados al reporte.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(
      related: User::class,
      foreignKey: 'user_id',
      ownerKey: 'id'
    );
  }

  /**
   * Obtiene el area asociado al reporte.
   */
  public function area(): BelongsTo
  {
    return $this->belongsTo(
      related: Area::class,
      foreignKey: 'area_id',
      ownerKey: 'id'
    );
  }

  /**
   * Obtiene el estado del reporte asociado al reporte.
   */
  public function reportStatus(): BelongsTo
  {
    return $this->belongsTo(
      related: ReportStatus::class,
      foreignKey: 'report_status_id',
      ownerKey: 'id'
    );
  }

  /**
   * Obtiene la empresa asociado al reporte.
   */
  public function enterprise(): BelongsTo
  {
    return $this->belongsTo(
      related: Enterprise::class,
      foreignKey: 'enterprise_id',
      ownerKey: 'id'
    );
  }

  /**
   * Obtiene el proyecto asociado al reporte.
   */
  public function project(): BelongsTo
  {
    return $this->belongsTo(
      related: Project::class,
      foreignKey: 'project_id',
      ownerKey: 'id'
    );
  }
}
