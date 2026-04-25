@extends('layouts.app', ['title' => 'สถานะรายงานการผ่าชันสูตรศพ'])

@push('styles')
<style>
/* ===== CARD ===== */
.card-clean {
    background: #fff;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

/* ===== FILTER ===== */
.filter-box {
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 16px;
    background: #fafafa;
}

/* ===== TABLE ===== */
.table-modern {
    width: 100%;
    border-collapse: collapse;
}

.table-modern thead th {
    font-size: 13px;
    color: #64748b;
    text-align: left;
    padding: 12px;
    font-weight: 700;
}

.table-modern tbody td {
    padding: 14px 12px;
    border-top: 1px solid #f1f5f9;
    vertical-align: middle;
}

/* ===== STATUS ===== */
.status-badge {
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.status-pending {
    background: #fef2f2;
    color: #dc2626;
}

.status-submitted {
    background: #fffbeb;
    color: #d97706;
}

.status-approve {
    background: #ecfdf5;
    color: #059669;
}

/* ===== BUTTON GROUP ===== */
.action-group {
    display: flex;
    gap: 6px;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 10px;
}

/* ===== BUTTON STYLE ===== */
.btn-outline {
    border: 1px solid #e2e8f0;
    background: #fff;
}

.btn-outline:hover {
    background: #f8fafc;
}

.btn-primary-soft {
    background: #0f172a;
    color: #fff;
}

.btn-primary-soft:hover {
    background: #1e293b;
}

/* ===== ROW HOVER ===== */
.table-modern tbody tr:hover {
    background: #f8fafc;
}

/* ===== FIX WIDTH ===== */
.col-status { width: 140px; }
.col-action { width: 200px; }
</style>
@endpush

@section('content')
<div class="card-clean">

    {{-- FILTER --}}
    <form method="GET" action="{{ route('approve-autopsy-cases.index') }}" class="filter-box">
        <div class="grid gap-4 md:grid-cols-3">

            <div>
                <label class="text-sm text-slate-600">สถานะ</label>
                <select name="status" class="input w-full">
                    <option value="">ทั้งหมด</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>รอเสนอแพทย์</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>เสนอแพทย์</option>
                    <option value="approve" {{ request('status') === 'approve' ? 'selected' : '' }}>เสร็จสมบูรณ์</option>
                </select>
            </div>

            <div>
                <label class="text-sm text-slate-600">แพทย์</label>
                <select name="doctor_id" class="input w-full">
                    <option value="">ทั้งหมด</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @selected(request('doctor_id') == $doctor->id)>
                            {{ $doctor->displayName() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-slate-600">เดือน</label>
                <input type="month" name="month" value="{{ request('month') }}" class="input w-full">
            </div>

        </div>

        <div class="mt-4 flex justify-end gap-2">
            <button class="btn btn-secondary">ค้นหา</button>
            <a href="{{ route('approve-autopsy-cases.index') }}" class="btn btn-secondary">ล้าง</a>
        </div>
    </form>

    {{-- TABLE --}}
    <table class="table-modern">
        <thead>
            <tr>
                <th>วันที่</th>
                <th>เลขที่</th>
                <th>ชื่อผู้เสียชีวิต</th>
                <th>แพทย์</th>
                <th>ส่งตรวจทางห้องปฎิบัติการ</th>
                <th>หมายเหตุ</th>
                <th class="col-status">สถานะ</th>
                <th class="col-action"></th>
            </tr>
        </thead>

        <tbody>
        @forelse ($cases as $case)

        
            @php
                $statusClass = match($case->status) {
                    'pending' => 'status-pending',
                    'submitted' => 'status-submitted',
                    'approve' => 'status-approve',
                    default => ''
                };        
                 $statusText = match($case->status) {
                    'pending' => 'รอเสนอแพทย์',
                    'submitted' => 'เสนอแพทย์',
                    'approve' => 'เสร็จสมบูรณ์',
                    default => '-',
                };
            @endphp

            <tr>
                <td>{{ optional($case->autopsy_date)->format('d/m/Y') }}</td>
                <td>{{ $case->autopsy_no }}</td>
                <td>{{ $case->scene->deceased_name ?? '-' }}</td>
                <td>{{ $case->doctor?->displayName() }}</td>
                <td>{{ $case->lab->name ?? '-' }}</td>
                <td>{{ $case->remarks ?: '-' }}</td>

                {{-- STATUS --}}
                <td>
                    <span class="status-badge {{ $statusClass }}">
                        {{ $statusText ?? $case->status }}
                    </span>
                </td>

                {{-- ACTION --}}
                <td>
                    <div class="action-group">

                        <a href="{{ route('autopsy-cases.show', $case) }}" class="btn btn-outline btn-sm">
                            ดู
                        </a>

                        @if(auth()->user()->hasRole('system') || auth()->user()->hasRole('admin'))
                            <a href="{{ route('autopsy-cases.edit', $case) }}" class="btn btn-outline btn-sm">
                                แก้ไข
                            </a>
                        @endif

                        @if($case->canAction)

                            @if(!$case->isExpired == true && auth()->user()->hasRole('staff'))
                                <a href="{{ route('autopsy-cases.edit', $case) }}" class="btn btn-outline btn-sm">
                                    แก้ไข
                                </a>
                            @endif

                            <a href="{{ route('approve-autopsy-cases.submitted', $case) }}"
                               class="btn btn-primary-soft btn-sm">

                                @if(auth()->user()->hasRole('doctor'))
                                    อนุมัติ
                                @else
                                    เสนอ
                                @endif

                            </a>

                        @endif

                    </div>
                </td>

            </tr>

        @empty
            <tr>
                <td colspan="8" class="text-center text-slate-400 py-10">
                    ไม่พบข้อมูล
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="mt-6">
        {{ $cases->links() }}
    </div>

</div>
@endsection