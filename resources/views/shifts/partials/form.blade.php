<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="label">วันที่</label>
        <input type="date" name="shift_date" value="{{ old('shift_date', isset($shift) ? $shift->shift_date->format('Y-m-d') : '') }}" class="input">
    </div>
    <div>
        <label class="label">ประเภทกะ</label>
        <select name="shift_type" class="input">
            <option value="">-- เลือกกะ --</option>
            <option value="day" @selected(old('shift_type', $shift->shift_type->value ?? '') === 'day')>กะกลางวัน (08:00 - 16:00)</option>
            <option value="night" @selected(old('shift_type', $shift->shift_type->value ?? '') === 'night')>กะกลางคืน (16:00 - 08:00)</option>
        </select>
    </div>
    <div>
        <label class="label">แพทย์</label>
        <select name="doctor_user_id" class="input">
            <option value="">-- เลือกแพทย์ --</option>
            @foreach ($doctors as $doctor)
                <option value="{{ $doctor->id }}" @selected(old('doctor_user_id', $shift->doctor_user_id ?? '') == $doctor->id)>
                    {{ $doctor->displayName() }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label">ผู้ช่วยแพทย์</label>
        <select name="assistant_user_id" class="input">
            <option value="">-- เลือกผู้ช่วยแพทย์ --</option>
            @foreach ($assistants as $assistant)
                <option value="{{ $assistant->id }}" @selected(old('assistant_user_id', $shift->assistant_user_id ?? '') == $assistant->id)>
                    {{ $assistant->displayName() }}
                </option>
            @endforeach
        </select>
    </div>
</div>
<div class="mt-4">
    <label class="label">หมายเหตุ</label>
    <textarea name="notes" rows="4" class="input">{{ old('notes', $shift->notes ?? '') }}</textarea>
</div>
