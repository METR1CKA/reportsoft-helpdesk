<?php

namespace App\Http\Requests\Enterprises;

use App\Models\Enterprise;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;

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
      'contact_name' => [
        'required',
        'string',
        'max:100',
      ],
      'contact_email' => [
        'required',
        'email',
        'string',
        'max:200',
        'unique:' . Enterprise::class
      ],
      'contact_phone' => [
        'required',
        'string',
        'max:15',
        'unique:' . Enterprise::class,
        'regex:/^\+\d{1,3}[- ]?\d{10}$/',
      ],
      'legal_name' => [
        'required',
        'string',
        'max:200',
        'unique:' . Enterprise::class
      ],
      'legal_id' => [
        'required',
        'string',
        'max:200',
        'unique:' . Enterprise::class
      ],
      'g-recaptcha-response' => ['required', new Recaptcha],
    ];
  }
}
