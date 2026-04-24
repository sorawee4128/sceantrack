<?php

namespace App\Http\Requests\SceneCases;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSceneCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $required = $this->isSubmitting() ? ['required'] : ['nullable'];

        return [
            'shift_id' => array_merge($required, ['exists:shifts,id']),
            'scene_no' => array_merge($required, ['string', 'max:100']),
            'case_date' => array_merge($required, ['date']),
            'notified_time' => ['nullable'],
            'arrival_time' => ['nullable'],
            'police_station_id' => array_merge($required, ['exists:police_stations,id']),
            'deceased_name' => ['nullable', 'string', 'max:150'],
            'gender_id' => ['nullable', 'exists:genders,id'],
            'age' => ['nullable', 'integer', 'min:0'],
            'body_handling_id' => array_merge($required, ['exists:body_handlings,id']),
            'notification_type_id' => array_merge($required, ['exists:notification_types,id']),
            'case_description' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
            'photos' => ['nullable', 'array', 'max:10'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'action' => ['required', Rule::in(['draft', 'submit'])],
        ];
    }

    public function messages(): array
    {
        return [
            'shift_id.required' => 'กรุณาเลือกกะเวร',
            'scene_no.required' => 'กรุณากรอกหมายเลขซีน',
            'case_date.required' => 'กรุณาเลือกวันที่เคส',
            'case_date.date' => 'รูปแบบวันที่ไม่ถูกต้อง',
            'police_station_id.required' => 'กรุณาเลือกสถานีตำรวจ',
            'body_handling_id.required' => 'กรุณาเลือกการจัดการศพ',
            'notification_type_id.required' => 'กรุณาเลือกประเภทที่แจ้ง',
            'notified_time.date_format' => 'รูปแบบเวลาไม่ถูกต้อง',
            'arrival_time.date_format' => 'รูปแบบเวลาไม่ถูกต้อง',
            'age.integer' => 'อายุต้องเป็นตัวเลข',
            'age.min' => 'อายุต้องไม่ติดลบ',
            'photos.max' => 'อัปโหลดรูปได้สูงสุด 10 รูป',
            'photos.*.image' => 'อัปโหลดได้เฉพาะไฟล์รูป',
            'photos.*.mimes' => 'อัปโหลดได้เฉพาะไฟล์ jpg/png/webp',
            'photos.*.max' => 'ขนาดไฟล์รูปต้องไม่เกิน 5 MB',
        ];
    }

    protected function isSubmitting(): bool
    {
        return $this->input('action') === 'submit';
    }
}
