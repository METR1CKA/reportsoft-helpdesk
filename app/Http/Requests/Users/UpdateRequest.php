<?php

namespace App\Http\Requests\Users;

use App\Models\User;
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
      'username' => ['required', 'string', 'max:255'],
      'email' => [
        'required',
        'string',
        'lowercase',
        'email',
        'max:255',
        Rule::unique(User::class)->ignore($this->id, 'id')
      ],
      'phone' => [
        'required',
        'string',
        'regex:/^\+\d{1,3}[- ]?\d{10}$/',
        Rule::unique(User::class)->ignore($this->id, 'id')
      ],
      'role_id' => [
        'required',
        'integer',
        Rule::exists('roles', 'id'),
      ],
      'g-recaptcha-response' => ['required', new Recaptcha],
    ];
  }
}
