<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePoliceStationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage master data');
    }

    public function rules(): array
    {
        return [
            'code' => ['nullable', 'string', 'max:50', Rule::unique('police_stations', 'code')],
            'name' => ['required', 'string', 'max:150', Rule::unique('police_stations', 'name')],
            'email' => ['string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'กรุณากรอก email',
            'name.required' => 'กรุณากรอกชื่อรายการ',
            'name.unique' => 'ชื่อรายการนี้มีอยู่แล้ว',
            'code.unique' => 'code นี้มีอยู่แล้ว',
        ];
    }
}
