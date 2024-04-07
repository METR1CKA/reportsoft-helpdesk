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
    $code = '';

    for ($i = 0; $i < $length; $i++) {
      $code .= rand(0, 9);
    }

    return $code;
  }

  /**
   * Generar un código de verificación string.
   *
   * @param int $length
   * @return string
   */
  public static function generateStringCode(int $length = 6): string
  {
    $letters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

    return substr(
      string: str_shuffle($letters),
      offset: 0,
      length: $length
    );
  }
}

