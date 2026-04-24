@extends('layouts.app', ['title' => 'สถานะรายงานการผ่าชันสูตรศพ'])

@section('content')
<div class="card">
    <form method="GET" action="{{ route('approve-autopsy-cases.index') }}" class="mb-4 rounded-2xl border border-slate-200 bg-white p-4">
        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">สถานะ</label>
                <select name="status" id="status" class="input w-full">
                    <option value="">ทั้งหมด</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>รอเสนอแพทย์</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>เสนอแพทย์</option>
                    <option value="approve" {{ request('status') === 'approve' ? 'selected' : '' }}>รายงานเสร็จสมบูรณ์</option>
                </select>
            </div>

            <div>
                <label for="doctor_id" class="mb-2 block text-sm font-semibold text-slate-700">แพทย์</label>
                <select name="doctor_id" id="doctor_id" class="input w-full">
                    <option value="">ทั้งหมด</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ (string) request('doctor_id') === (string) $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->displayName() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="month" class="mb-2 block text-sm font-semibold text-slate-700">ช่วงเวลา</label>
                <input
                    type="month"
                    name="month"
                    id="month"
                    value="{{ request('month') }}"
                    class="input w-full"
                >
            </div>
        </div>

        <div class="mt-4 flex flex-wrap items-center justify-end gap-2">
            <button type="submit" class="btn btn-secondary">ค้นหา</button>
            <a href="{{ route('approve-autopsy-cases.index') }}" class="btn btn-secondary">ล้างค่า</a>
        </div>
    </form>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>วันที่ผ่าชันสูตรศพ</th>
                    <th>หมายเลขผ่าชันสูตรศพ</th>
                    <th>ชื่อผู้เสียชีวิต</th>
                    <th>แพทย์</th>
                    <th>รายการตรวจห้องปฎิบัติการ</th>
                    <th>หมายเหตุ</th>
                    <th>สถานะ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($cases as $case)
                    <tr>
                        <td class="font-medium">
                            {{ $case->autopsy_date ? \Carbon\Carbon::parse($case->autopsy_date)->format('d/m/Y') : '-' }}
                        </td>
                        <td>{{ $case->autopsy_no ?? '-' }}</td>
                        <td>{{ $case->scene->deceased_name ?? '-' }}</td>
                        <td>{{ $case->doctor?->displayName() ?? '-' }}</td>
                        <td>{{ $case->lab->name ?? '-' }}</td>
                        <td>{{ $case->remarks ?: '-' }}</td>

                        @php
                            $statusText = match($case->status) {
                                'pending' => 'รอเสนอแพทย์',
                                'submitted' => 'เสนอแพทย์',
                                'approve' => 'รายงานเสร็จสมบูรณ์',
                                default => '-',
                            };

                            $statusClass = match($case->status) {
                                'pending' => 'bg-red-50 text-red-600 ring-red-300',
                                'submitted' => 'bg-yellow-50 text-yellow-600 ring-yellow-300',
                                'approve' => 'bg-green-50 text-green-600 ring-green-300',
                                default => 'bg-gray-50 text-gray-600 ring-gray-300',
                            };
                        @endphp

                        <td>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium ring-1 ring-inset {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>

                        <td class="text-right">
                            @if($case->canAction == false && auth()->user()->hasRole('staff'))
                                <a href="{{ route('autopsy-cases.show', $case) }}" class="btn btn-secondary">
                                    ดู
                                </a>
                            @elseif(auth()->user()->hasRole('doctor') || auth()->user()->hasRole('admin'))
                                <a href="{{ route('autopsy-cases.show', $case) }}" class="btn btn-secondary">
                                    ดู
                                </a>
                            @endif
                                @if(auth()->user()->hasRole('admin'))
                                <br>
                                <br>
                                    <a href="{{ route('autopsy-cases.edit', $case->id) }}" class="btn btn-secondary">
                                      แก้ไข
                                    </a>
                                @endif
                            @if($case->canAction)
                                @if(auth()->user()->hasRole('staff'))
                                    <a href="{{ route('autopsy-cases.edit', $case->id) }}" class="btn btn-secondary">
                                      แก้ไข
                                    </a>
                                @endif
                                <a href="{{ route('approve-autopsy-cases.submitted', $case->id) }}" class="btn btn-secondary">
                                    @if(auth()->user()->hasRole('doctor'))
                                        รายงานเสร็จสมบูรณ์
                                    @elseif(auth()->user()->hasRole('staff'))
                                        เสนอแพทย์
                                    @endif
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-slate-500">ไม่พบข้อมูล</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $cases->links() }}
    </div>
</div>
@endsection