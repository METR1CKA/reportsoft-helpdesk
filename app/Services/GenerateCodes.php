<?php

namespace App\Services;

class GenerateCodes
{
  /**
   * Generar un código de verificación númerico.
   *
   * @param int $length
   * @return string
   */
  public static function generateNumberCode(int $length = 6): string
  {
    $min = pow(10, $length - 1);
    $max = pow(10, $length) - 1;
    return strval(rand($min, $max));
  }

  /**
   * Generar un código de verificación string.
   *
   * @param int $length
   * @return string
   */
  public static function generateStringCode(int $length = 6): string
  {
    return substr(
      str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),
      0,
      $length
    );
  }
}

