<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthFA extends Model
{
  use HasFactory;

  /**
   * La tabla asociada con el modelo.
   *
   * @var string
   */
  protected $table = 'auth_fa';

  /**
   * Los atributos que son asignables en masa.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'user_id',
    'type',
    'code',
    'code_verified',
  ];

  /**
   * Obtiene el usuario asociado.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(
      related: User::class,
      foreignKey: 'user_id',
      ownerKey: 'id',
    );
  }
}
