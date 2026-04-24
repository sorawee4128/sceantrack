<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAutopsyAssistantRequest extends FormRequest
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
            'name.required' => 'กรุณากรอกชื่อผู้ช่วยผ่า',
            'name.unique' => 'ชื่อผู้ช่วยผ่านี้มีอยู่แล้ว',
        ];
    }
}
