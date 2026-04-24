<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage users');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'full_name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'position' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'role' => ['required', 'exists:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'กรุณากรอกชื่อสำหรับเข้าสู่ระบบ',
            'full_name.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'username.required' => 'กรุณากรอก username',
            'username.unique' => 'username นี้ถูกใช้งานแล้ว',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.confirmed' => 'ยืนยันรหัสผ่านไม่ตรงกัน',
            'role.required' => 'กรุณาเลือก role',
        ];
    }
}
