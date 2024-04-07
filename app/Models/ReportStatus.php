<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportStatus extends Model
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
   * Obtiene los reportes asociados al area.
   */
  public function reports(): HasMany
  {
    return $this->hasMany(
      related: Report::class,
      foreignKey: 'report_status_id',
      localKey: 'id'
    );
  }
}
