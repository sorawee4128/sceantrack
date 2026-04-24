<?php

namespace App\Http\Requests\Shifts;

use App\Models\Shift;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage shifts');
    }

    public function rules(): array
    {
        return [
            'shift_date' => ['required', 'date'],
            'shift_type' => ['required', Rule::in(['day', 'night'])],
            'doctor_user_id' => ['required', 'exists:users,id'],
            'assistant_user_id' => ['required', 'exists:users,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $shiftId = $this->route('shift')->id;

            $exists = Shift::query()
                ->whereDate('shift_date', $this->shift_date)
                ->where('shift_type', $this->shift_type)
                ->whereKeyNot($shiftId)
                ->exists();

            if ($exists) {
                $validator->errors()->add('shift_type', 'วันนี้มีกะประเภทนี้แล้ว');
            }
        });
    }

    public function messages(): array
    {
        return [
            'shift_date.required' => 'กรุณาเลือกวันที่',
            'shift_date.date' => 'รูปแบบวันที่ไม่ถูกต้อง',
            'shift_type.required' => 'กรุณาเลือกกะเวร',
            'doctor_user_id.required' => 'กรุณาเลือก doctor',
            'assistant_user_id.required' => 'กรุณาเลือก assistant',
        ];
    }
}
