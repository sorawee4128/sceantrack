<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage roles');
    }

    public function rules(): array
    {
        $roleId = $this->route('role')->id;

        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('roles', 'name')->ignore($roleId)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'กรุณากรอกชื่อ role',
            'name.unique' => 'role นี้มีอยู่แล้ว',
        ];
    }
}
