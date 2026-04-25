<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('view all reports scene') || $this->user()->can('view own reports scene');
    }

    public function rules(): array
    {
        return [
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
            'status' => ['nullable', 'in:draft,submitted'],
        ];
    }

    public function messages(): array
    {
        return [
            'from_date.date' => 'รูปแบบวันที่เริ่มต้นไม่ถูกต้อง',
            'to_date.date' => 'รูปแบบวันที่สิ้นสุดไม่ถูกต้อง',
            'to_date.after_or_equal' => 'from date ต้องไม่มากกว่า to date',
        ];
    }
}
