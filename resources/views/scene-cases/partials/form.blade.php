@php
    $selectedShiftId = old('shift_id', $sceneCase->shift_id ?? '');
    $selectedShift = isset($sceneCase) ? $sceneCase->shift : null;
@endphp

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<style>
/* ===== MINIMAL SELECT ===== */
.ts-wrapper {
    width: 100%;
}
.ts-dropdown .option[data-value=""] {

    display: none;

}
.ts-wrapper.single .ts-control {
    min-height: 48px;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    background: #fff;
    padding: 0 2.5rem 0 0.9rem;
    font-size: 0.875rem;
    font-weight: 400;
    color: #0f172a;
    box-shadow: none;
    display: flex;
    align-items: center;
    transition: all .15s ease;
}

/* hover */
.ts-wrapper.single .ts-control:hover {
    border-color: #cbd5e1;
}

/* focus */
.ts-wrapper.focus .ts-control {
    border-color: #94a3b8;
    box-shadow: 0 0 0 2px rgba(148, 163, 184, .15);
}

/* input text */
.ts-control input {
    font-size: 0.875rem;
}

/* arrow */
.ts-wrapper.single .ts-control::after {
    border-color: #94a3b8 transparent transparent transparent;
    right: 0.75rem;
}

/* ===== DROPDOWN ===== */
.ts-dropdown {
    margin-top: 6px;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    background: #fff;
    box-shadow: 0 10px 25px rgba(15, 23, 42, .08);
    overflow: hidden;
    font-size: 0.875rem;
}

/* option */
.ts-dropdown .option {
    padding: 10px 14px;
    color: #334155;
}

/* hover */
.ts-dropdown .option:hover {
    background: #f8fafc;
}

/* active */
.ts-dropdown .active {
    background: #f1f5f9;
    color: #0f172a;
}

/* selected */
.ts-dropdown .selected {
    background: #eef2ff;
    color: #2563eb;
}

/* no result */
.ts-dropdown .no-results {
    padding: 10px 14px;
    color: #94a3b8;
}

/* remove shadow default */
.ts-wrapper .ts-control,
.ts-wrapper .ts-dropdown {
    box-shadow: none !important;
}
</style>
@endpush

<div x-data="sceneCaseForm()" x-init="init('{{ $selectedShiftId }}')" class="space-y-6">
    <div class="form-section">
        <div class="mb-4">
            <h2 class="card-title">ข้อมูลอ้างอิงเวรและผู้ปฏิบัติงาน</h2>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">กะเวร</label>
                <select name="shift_id" x-model="shiftId" @change="loadShift()" class="form-select searchable-select">
                    <option value="">-- เลือกกะเวร --</option>
                    @foreach ($shifts as $shift)
                        <option value="{{ $shift->id }}" @selected($selectedShiftId == $shift->id)>
                            {{ $shift->shift_date->format('d/m/Y') }} - {{ $shift->shift_type->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">หมายเลขชันสูตรศพ</label>
                <input
                    type="text"
                    name="scene_no"
                    value="{{ old('scene_no', $sceneCase->scene_no ?? '') }}"
                    class="form-input"
                >
            </div>

            <div class="form-group">
                <label class="form-label">แพทย์</label>
                <input type="text" x-model="doctorName" class="form-input bg-slate-50" readonly>
            </div>

            <div class="form-group">
                <label class="form-label">ผู้ช่วยแพทย์</label>
                <input type="text" x-model="assistantName" class="form-input bg-slate-50" readonly>
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="mb-4">
            <h2 class="card-title">ข้อมูลเคส</h2>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">วันที่ชันสูตรพลิกศพ</label>
                <input
                    type="date"
                    name="case_date"
                    value="{{ old('case_date', isset($sceneCase) && $sceneCase->case_date ? $sceneCase->case_date->format('Y-m-d') : '') }}"
                    class="form-input"
                >
            </div>

            <div class="form-group">
                <label class="form-label">สถานีตำรวจ</label>
                <select name="police_station_id" class="form-select searchable-select">
                    <option value="">-- เลือกสถานีตำรวจ --</option>
                    @foreach ($policeStations as $item)
                        <option value="{{ $item->id }}" @selected(old('police_station_id', $sceneCase->police_station_id ?? '') == $item->id)>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">เวลารับแจ้ง</label>
                <input
                    type="time"
                    name="notified_time"
                    value="{{ old('notified_time', $sceneCase->notified_time ?? '') }}"
                    class="form-input"
                >
            </div>

            <div class="form-group">
                <label class="form-label">เวลาที่ชันสูตร</label>
                <input
                    type="time"
                    name="arrival_time"
                    value="{{ old('arrival_time', $sceneCase->arrival_time ?? '') }}"
                    class="form-input"
                >
            </div>

            <div class="form-group">
                <label class="form-label">ชื่อผู้เสียชีวิต</label>
                <input
                    type="text"
                    name="deceased_name"
                    value="{{ old('deceased_name', $sceneCase->deceased_name ?? '') }}"
                    class="form-input"
                >
            </div>

            <div class="form-group">
                <label class="form-label">เพศ</label>
                <select name="gender_id" class="form-select searchable-select">
                    <option value="">-- เลือกเพศ --</option>
                    @foreach ($genders as $item)
                        <option value="{{ $item->id }}" @selected(old('gender_id', $sceneCase->gender_id ?? '') == $item->id)>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">อายุ</label>
                <input
                    type="number"
                    min="0"
                    name="age"
                    value="{{ old('age', $sceneCase->age ?? '') }}"
                    class="form-input"
                >
            </div>

            <div class="form-group">
                <label class="form-label">การจัดการศพ</label>
                <select name="body_handling_id" class="form-select searchable-select">
                    <option value="">-- เลือกการจัดการศพ --</option>
                    @foreach ($bodyHandlings as $item)
                        <option value="{{ $item->id }}" @selected(old('body_handling_id', $sceneCase->body_handling_id ?? '') == $item->id)>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">ประเภทที่แจ้ง</label>
                <select name="notification_type_id" class="form-select searchable-select">
                    <option value="">-- เลือกประเภทที่แจ้ง --</option>
                    @foreach ($notificationTypes as $item)
                        <option value="{{ $item->id }}" @selected(old('notification_type_id', $sceneCase->notification_type_id ?? '') == $item->id)>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 form-group">
            <label class="form-label">รายละเอียดเคส</label>
            <textarea name="case_description" rows="4" class="form-textarea">{{ old('case_description', $sceneCase->case_description ?? '') }}</textarea>
        </div>

        <div class="mt-4 form-group">
            <label class="form-label">หมายเหตุ</label>
            <textarea name="remarks" rows="3" class="form-textarea">{{ old('remarks', $sceneCase->remarks ?? '') }}</textarea>
        </div>
    </div>

    <div x-data="photoUploader()" class="form-section">
        <div class="mb-4">
            <h2 class="card-title">รูปประกอบเคส</h2>
            <p class="page-subtitle">รองรับ jpg / png / webp ได้สูงสุด 10 รูป</p>
        </div>

        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6">
            <input
                id="photos"
                name="photos[]"
                type="file"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                multiple
                class="hidden"
                x-ref="photos"
                @change="handleFiles"
            >

            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="text-sm font-medium text-slate-700">เลือกรูปจากเครื่อง</div>
                    <div class="text-sm text-slate-500">สามารถเลือกได้หลายไฟล์ในครั้งเดียว</div>
                </div>

                <button type="button" class="btn btn-secondary" @click="$refs.photos.click()">
                    เลือกไฟล์
                </button>
            </div>

            <div class="mt-4 rounded-xl bg-white px-4 py-3 text-sm text-slate-500" x-show="files.length === 0">
                ยังไม่ได้เลือกไฟล์
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3" x-show="files.length > 0">
                <template x-for="(file, index) in files" :key="index">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="aspect-[4/3] overflow-hidden bg-slate-100">
                            <img :src="file.url" :alt="file.name" class="h-full w-full object-cover">
                        </div>
                        <div class="space-y-1 p-3">
                            <div class="truncate text-sm font-medium text-slate-800" x-text="file.name"></div>
                            <div class="text-xs text-slate-500" x-text="file.size"></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        @error('photos')
            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
        @enderror

        @error('photos.*')
            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
        @enderror

        @if(isset($sceneCase) && $sceneCase->photos->count())
            <div class="mt-6">
                <h3 class="mb-3 text-base font-semibold">รูปที่อัปโหลดแล้ว</h3>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($sceneCase->photos as $photo)
                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <img src="{{ $photo->url() }}" alt="{{ $photo->file_name }}" class="h-44 w-full object-cover">

                            <div class="flex items-center justify-between gap-3 p-3">
                                <div class="truncate text-sm text-slate-700">{{ $photo->file_name }}</div>

                                <button
                                    type="button"
                                    class="btn btn-danger"
                                    @click.prevent.stop="deletePhoto(@js(route('scene-case-photos.destroy', ['photo' => $photo->id])))"
                                >
                                    ลบ
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function sceneCaseForm() {
            return {
                shiftId: '',
                doctorName: @js(old('doctor_name', $selectedShift?->doctor?->displayName() ?? '')),
                assistantName: @js(old('assistant_name', $selectedShift?->assistant?->displayName() ?? '')),
                loadingShift: false,

                init(selectedShiftId) {
                    this.shiftId = selectedShiftId || '';
                    if (this.shiftId) this.loadShift();
                },

                async loadShift() {
                    if (this.loadingShift) return;

                    if (!this.shiftId) {
                        this.doctorName = '';
                        this.assistantName = '';
                        return;
                    }

                    this.loadingShift = true;

                    try {
                        const response = await fetch('{{ url('scene-case-shifts') }}/' + this.shiftId, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Failed to load shift');
                        }

                        const data = await response.json();

                        this.doctorName = data.doctor_name ?? '';
                        this.assistantName = data.assistant_name ?? '';
                    } catch (error) {
                        this.doctorName = '';
                        this.assistantName = '';
                        console.error(error);
                    } finally {
                        this.loadingShift = false;
                    }
                }
            }
        }

        function photoUploader() {
            return {
                files: [],
                deleting: false,

                handleFiles(event) {
                    const selected = Array.from(event.target.files || []);

                    this.files.forEach(file => {
                        if (file.url) URL.revokeObjectURL(file.url);
                    });

                    this.files = selected.slice(0, 10).map(file => ({
                        name: file.name,
                        size: (file.size / 1024 / 1024).toFixed(2) + ' MB',
                        url: URL.createObjectURL(file),
                    }));
                },

                async deletePhoto(url) {
                    if (!url || typeof url !== 'string') {
                        alert('ไม่พบ URL สำหรับลบรูป');
                        return;
                    }

                    if (this.deleting) return;
                    if (!confirm('ลบรูปนี้?')) return;

                    this.deleting = true;

                    try {
                        const response = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`Delete failed: ${response.status}`);
                        }

                        window.location.reload();
                    } catch (error) {
                        console.error(error);
                        alert('ลบรูปไม่สำเร็จ');
                    } finally {
                        this.deleting = false;
                    }
                }
            }
        }
    </script>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.searchable-select').forEach(function (el) {
        if (el.tomselect) return;

        new TomSelect(el, {
            create: false,
            allowEmptyOption: true,
            placeholder: 'ค้นหา...',
            maxOptions: 1000,
            searchField: ['text'],
            hideSelected: true,

            render: {
                no_results: function () {
                    return '<div class="no-results">ไม่พบข้อมูล</div>';
                }
            },

            onFocus: function () {
                if (this.getValue() === '') {
                    this.clear(true);
                    this.setTextboxValue('');
                }
            },

            onType: function (str) {
                if (this.getValue() !== '') {
                    this.clear(false);
                    this.setTextboxValue(str);
                    this.refreshOptions(false);
                }
            }
        });
    });
});
</script>
@endpush