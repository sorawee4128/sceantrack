<div x-data="sceneCaseForm()" class="space-y-6">
    <div class="form-section">
        <div class="mb-4">
            <h2 class="card-title">บันทึกข้อมูลการผ่าชันสูตรศพ</h2>
        </div>

        <div class="form-group">
            <label class="form-label">ประเภทการผ่าชันสูตรศพ</label>
            <input type="text"  value="{{ $sceneCase->bodyHandling->name }}" class="form-input" readonly>
        </div>

        <div class="mt-4 form-grid">
          
            <div class="form-group">
                <label class="form-label">หมายเลขการผ่าชันสูตรศพ</label>
                <input type="text" name="autopsy_no" value="{{ old('autopsy_no', isset($autopsyCase->autopsy_no) ? substr($autopsyCase->autopsy_no, 2) : '') }}" class="form-input">
                <input type="text" name="scene_case_id" value="{{ $sceneCase->id }}" class="form-input" hidden>
                 <input type="text" name="body_handling_code" value="{{ $sceneCase->bodyHandling->code }}" class="form-input" hidden>
            </div>

            <div class="form-group">
                <label class="form-label">สถานีตำรวจ</label>
                <select name="police_station_id" class="form-select">
                    <option value="">-- เลือกสถานีตำรวจ --</option>
                    @foreach ($policeStations as $item)
                        <option value="{{ $item->id }}" @selected(old('police_station_id', $autopsyCase->police_station_id ?? '') == $item->id)>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">แพทย์ผู้ผ่าพิสูจน์</label>
                <select name="doctor_user_id" class="form-select">
                    <option value="">-- เลือกแพทย์ผู้ผ่าพิสูจน์ --</option>
                    @foreach ($doctors as $item)
                        <option value="{{ $item->id }}" @selected(old('doctor_user_id', $autopsyCase->doctor_user_id ?? '') == $item->id)>
                            {{ $item->displayName() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">วันที่ผ่าพิสูจน์</label>
                <input type="date" name="autopsy_date"  value="{{ old('autopsy_date', isset($autopsyCase) && $autopsyCase->autopsy_date ? $autopsyCase->autopsy_date->format('Y-m-d') : '') }}" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">รูปแบบการผ่า</label>
                <select name="autopsy_method" class="form-select">
                    <option value="">-- เลือกรูปแบบการผ่า --</option>
                    <option value="autopsy" @selected(old('autopsy_method', $autopsyCase->autopsy_method ?? '') == "autopsy")>ผ่า</option>
                    <option value="no_autopsy" @selected(old('autopsy_method', $autopsyCase->autopsy_method ?? '') == "no_autopsy")>ไม่ผ่า</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">ผู้ช่วยผ่า</label>
                <select name="autopsy_assistant_id" class="form-select">
                    <option value="">-- เลือกผู้ช่วยผ่า --</option>
                    @foreach ($autopsyAssistants as $item)
                        <option value="{{ $item->id }}" @selected(old('autopsy_assistant_id', $autopsyCase->autopsy_assistant_id ?? '') == $item->id)>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">ผู้ช่วยถ่ายภาพ</label>
                <select name="photo_assistant_id" class="form-select">
                    <option value="">-- เลือกผู้ช่วยถ่ายภาพ --</option>
                    @foreach ($photoAssistants as $item)
                        <option value="{{ $item->id }}" @selected(old('photo_assistant_id', $autopsyCase->photo_assistant_id ?? '') == $item->id)>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">ส่งตรวจทางห้องปฎิบัติการ</label>
                <select name="lab_id" class="form-select">
                    <option value="">-- เลือกห้องปฎิบัติการ --</option>
                    @foreach ($labs as $item)
                        <option value="{{ $item->id }}" @selected(old('lab_id', $autopsyCase->lab_id ?? '') == $item->id)>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 form-group">
            <label class="form-label">บันทึกเพิ่มเติม</label>
            <textarea name="remarks" rows="3" class="form-textarea">{{ old('remarks', $autopsyCase->remarks ?? '') }}</textarea>
        </div>
    </div>

    <script>
    </script>
</div>