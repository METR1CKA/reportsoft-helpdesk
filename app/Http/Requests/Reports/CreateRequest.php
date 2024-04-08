<?php

namespace App\Http\Requests\Reports;

use Illuminate\Validation\Rule;
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
      'user_id' => [
        'required',
        'integer',
        Rule::exists('users', 'id')
      ],
      'area_id' => [
        'required',
        'integer',
        Rule::exists('areas', 'id')
      ],
      'enterprise_id' => [
        'required',
        'integer',
        Rule::exists('enterprises', 'id')
      ],
      'project_id' => [
        'required',
        'integer',
        Rule::exists('projects', 'id')
      ],
      'report_status_id' => [
        'required',
        'integer',
        Rule::exists('report_statuses', 'id')
      ],
      'name' => [
        'required',
        'string',
        'max:200'
      ],
      'description' => [
        'required',
        'string'
      ],
      'comments' => [
        'required',
        'string'
      ],
      'g-recaptcha-response' => ['required', new Recaptcha],
    ];
  }
}
