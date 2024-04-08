<?php

namespace App\Http\Requests\Enterprises;

use App\Models\Enterprise;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\Recaptcha;

class UpdateRequest extends FormRequest
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
        Rule::unique(Enterprise::class)->ignore($this->id, 'id')
      ],
      'contact_phone' => [
        'required',
        'string',
        'max:15',
        Rule::unique(Enterprise::class)->ignore($this->id, 'id'),
        'regex:/^\+\d{1,3}[- ]?\d{10}$/',
      ],
      'legal_name' => [
        'required',
        'string',
        'max:200',
        Rule::unique(Enterprise::class)->ignore($this->id, 'id')
      ],
      'legal_id' => [
        'required',
        'string',
        'max:200',
        Rule::unique(Enterprise::class)->ignore($this->id, 'id')
      ],
      'g-recaptcha-response' => ['required', new Recaptcha],
    ];
  }
}
