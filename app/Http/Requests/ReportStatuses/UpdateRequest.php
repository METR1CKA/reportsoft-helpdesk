<?php

namespace App\Http\Requests\ReportStatuses;

use App\Models\ReportStatus;
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
      'name' => ['required', 'string', 'max:255', Rule::unique(ReportStatus::class)->ignore($this->id, 'id')],
      'description' => ['required', 'string', 'max:255',],
      'g-recaptcha-response' => ['required', new Recaptcha],
    ];
  }
}
