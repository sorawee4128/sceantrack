<?php

namespace App\Http\Requests\Permissions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage permissions');
    }

    public function rules(): array
    {
        $permissionId = $this->route('permission')->id;

        return [
            'name' => ['required', 'string', 'max:150', Rule::unique('permissions', 'name')->ignore($permissionId)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'กรุณากรอกชื่อ permission',
            'name.unique' => 'permission นี้มีอยู่แล้ว',
        ];
    }
}
