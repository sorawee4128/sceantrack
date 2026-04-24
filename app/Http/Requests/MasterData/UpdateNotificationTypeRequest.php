<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNotificationTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('manage master data');
    }

    public function rules(): array
    {
        $routeParam = $this->route('notification_type')
            ?? $this->route('notificationType')
            ?? $this->route('id');

        $id = is_object($routeParam) ? $routeParam->id : $routeParam;

        return [
            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('notification_types', 'code')->ignore($id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'กรุณากรอกชื่อประเภทที่แจ้ง',
            'code.unique' => 'รหัสนี้ถูกใช้งานแล้ว',
            'is_active.required' => 'กรุณาระบุสถานะการใช้งาน',
            'is_active.boolean' => 'สถานะการใช้งานไม่ถูกต้อง',
        ];
    }
}