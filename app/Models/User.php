<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo para los usuarios.
 */
class User extends Authenticatable implements MustVerifyEmail
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * Los atributos que son asignables en masa.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'username',
    'email',
    'password',
    'active',
    'phone',
  ];

  /**
   * Los atributos que deben ocultarse para la serialización.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Los atributos que deben convertirse a tipos nativos.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  /**
   * Obtiene el rol del usuario.
   */
  public function role(): BelongsToMany
  {
    return $this->belongsToMany(
      related: Role::class,
      table: 'users_roles',
      foreignPivotKey: 'user_id',
      relatedPivotKey: 'role_id',
    );
  }

  /**
   * Obtiene el factor de autenticación asociado al usuario.
   */
  public function authFA(): HasMany
  {
    return $this->hasMany(
      related: AuthFA::class,
      foreignKey: 'user_id',
      localKey: 'id',
    );
  }
}
