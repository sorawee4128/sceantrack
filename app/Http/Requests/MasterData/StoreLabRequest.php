<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLabRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage master data');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150', Rule::unique('labs', 'name')],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'กรุณากรอกชื่อห้องปฎิบัติการ',
            'name.unique' => 'ชื่อห้องปฎิบัติการนี้มีอยู่แล้ว',
        ];
    }
}
