<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGenderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('manage master data');
    }

    public function rules(): array
    {
        $routeParam = $this->route('gender')
            ?? $this->route('id');

        $id = is_object($routeParam) ? $routeParam->id : $routeParam;

        return [
            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('genders', 'code')->ignore($id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'กรุณากรอกชื่อเพศ',
            'code.unique' => 'รหัสนี้ถูกใช้งานแล้ว',
            'is_active.required' => 'กรุณาระบุสถานะการใช้งาน',
            'is_active.boolean' => 'สถานะการใช้งานไม่ถูกต้อง',
        ];
    }
}