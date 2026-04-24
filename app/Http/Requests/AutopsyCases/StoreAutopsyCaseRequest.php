<?php

namespace App\Http\Requests\AutopsyCases;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAutopsyCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage autopsy cases')
            || $this->user()->can('submit autopsy cases')
            || $this->user()->can('edit own draft');
    }

    public function rules(): array
    {
        $required = $this->isSubmitting() ? ['required'] : ['nullable'];

        return [
            'autopsy_no' => array_merge($required, ['string', 'max:100']),
            'autopsy_date' => array_merge($required, ['date']),
            'police_station_id' => array_merge($required, ['exists:police_stations,id']),
            'doctor_user_id' => ['required', 'exists:users,id'],
            'autopsy_assistant_id' => array_merge($required, ['exists:autopsy_assistants,id']),
            'photo_assistant_id' => array_merge($required, ['exists:photo_assistants,id']),
            'lab_id' => array_merge($required, ['exists:labs,id']),
            'autopsy_method' => array_merge($required, ['string', 'max:100']),
            'remarks' => ['nullable', 'string'],
            'scene_case_id' => ['integer'],
            'action' => ['required', Rule::in(['draft', 'submit'])],
        ];
    }

    public function messages(): array
    {
        return [
            'autopsy_no.required' => 'กรุณากรอกหมายเลข Autopsy',
            'autopsy_date.required' => 'กรุณาเลือกวันที่วันที่ผ่าพิสูจน์',
            'autopsy_date.date' => 'รูปแบบวันที่ไม่ถูกต้อง',
            'police_station_id.required' => 'กรุณาเลือกสถานีตำรวจ',
            'doctor_user_id.required' => 'กรุณาเลือกแพทย์ผู้ผ่าพิสูจน์',
            'autopsy_assistant_id.required' => 'กรุณาเลือกผู้ช่วยผ่า',
            'photo_assistant_id.required' => 'กรุณาเลือกผู้ช่วยถ่ายภาพ',
            'lab_id.required' => 'กรุณาเลือกห้องปฎิบัติการ',
            'autopsy_method.required' => 'กรุณาเลือกรูปแบบการผ่า',
        ];
    }

    protected function isSubmitting(): bool
    {
        return $this->input('action') === 'submit';
    }
}
