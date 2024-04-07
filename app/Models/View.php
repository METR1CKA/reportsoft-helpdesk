<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class View extends Model
{
  use HasFactory;

  /**
   * Los atributos que son asignables en masa.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'category_id',
    'icon',
    'path',
    'component',
    'name',
    'order_index',
    'description',
    'active'
  ];

  /**
   * Obtiene los roles asociados a la vista.
   */
  public function roles(): BelongsToMany
  {
    return $this->belongsToMany(
      related: Role::class,
      table: 'roles_views',
      foreignPivotKey: 'view_id',
      relatedPivotKey: 'role_id',
    );
  }

  /**
   * Obtiene la categoria asociados a la vista.
   */
  public function category(): BelongsTo
  {
    return $this->belongsTo(
      related: Category::class,
      foreignKey: 'category_id',
      ownerKey: 'id'
    );
  }
}
