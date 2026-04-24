<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePhotoAssistantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('manage master data');
    }

    public function rules(): array
    {
        $routeParam = $this->route('lab')
            ?? $this->route('id');

        $id = is_object($routeParam) ? $routeParam->id : $routeParam;

        return [
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'กรุณากรอกชื่อผู้ช่วยถ่ายภาพ',
            'is_active.required' => 'กรุณาระบุสถานะการใช้งาน',
            'is_active.boolean' => 'สถานะการใช้งานไม่ถูกต้อง',
        ];
    }
}