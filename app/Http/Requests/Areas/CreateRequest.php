<?php

namespace App\Http\Requests\Areas;

use App\Models\Area;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Recaptcha;

class CreateRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255', 'unique:' . Area::class],
      'description' => ['required', 'string', 'max:255',],
      'g-recaptcha-response' => ['required', new Recaptcha],
    ];
  }
}
