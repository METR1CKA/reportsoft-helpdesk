<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Modelo para los roles.
 */
class Role extends Model
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
   * Obtiene los usuarios asociados al rol.
   */
  public function users(): BelongsToMany
  {
    return $this->belongsToMany(
      related: User::class,
      table: 'users_roles',
      foreignPivotKey: 'role_id',
      relatedPivotKey: 'user_id',
    );
  }

  /**
   * Obtiene las vistas asociados al rol.
   */
  public function views(): BelongsToMany
  {
    return $this->belongsToMany(
      related: View::class,
      table: 'roles_views',
      foreignPivotKey: 'role_id',
      relatedPivotKey: 'view_id',
    );
  }

  /**
   * Obtiene los roles de la base de datos.
   */
  public static function getRoles()
  {
    $current_roles = DB::table('roles')
      ->select('id', 'name')
      ->orderBy('id', 'asc')
      ->get();

    $current_roles_array = $current_roles->toArray();

    $values = array_reduce($current_roles_array, function ($carry, $role) {
      $carry[$role->name] = $role->id;
      return $carry;
    }, []);

    return $values;
  }
}
