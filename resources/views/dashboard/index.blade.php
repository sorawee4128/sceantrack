@extends('layouts.app', ['title' => 'หน้าหลัก'])
@push('styles')
<style>
    .calendar-wrap {
        width: 100%;
        min-width: 0;
    }

    .calendar-card {
        width: 100%;
        min-width: 0;
    }

    #calendar.shift-calendar {
        width: 100%;
        min-width: 0;
    }

    .shift-calendar .fc {
        width: 100% !important;
        font-size: 0.875rem;
    }

    .shift-calendar .fc-view-harness,
    .shift-calendar .fc-scrollgrid,
    .shift-calendar .fc-col-header,
    .shift-calendar .fc-daygrid-body,
    .shift-calendar .fc-daygrid-body table {
        width: 100% !important;
    }

    .shift-calendar .fc-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .shift-calendar .fc-toolbar-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
    }

    .shift-calendar .fc-daygrid-day-frame {
        min-height: 8rem;
    }

    .shift-calendar .fc-col-header-cell-cushion {
        padding: 0.75rem 0.25rem;
        font-weight: 700;
    }

    .shift-calendar .fc-daygrid-day-top {
        margin-bottom: 0.25rem;
    }

    .shift-calendar .fc-daygrid-event {
        border: 0 !important;
        border-radius: 0.75rem !important;
        padding: 0 !important;
        margin-top: 0.25rem !important;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
        background: transparent !important;
    }

    .shift-calendar .fc-h-event .fc-event-main {
        padding: 0 !important;
    }

    .shift-calendar .shift-event {
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
        padding: 0.45rem 0.6rem;
        line-height: 1.2;
        white-space: normal;
    }

    .shift-calendar .shift-event-day {
        background: #2563eb;
        color: #fff;
    }

    .shift-calendar .shift-event-night {
        background: #0f172a;
        color: #fff;
    }

    .shift-calendar .shift-event-type {
        font-size: 0.78rem;
        font-weight: 700;
    }

    .shift-calendar .shift-event-name {
        font-size: 0.72rem;
        opacity: 0.95;
    }

    .shift-calendar .fc-more-link {
        font-size: 0.75rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .shift-calendar .fc-toolbar-title {
            font-size: 1.25rem;
        }

        .shift-calendar .fc-daygrid-day-frame {
            min-height: 6rem;
        }
    }
</style>
@endpush
@section('content')

{{-- =======================
    STATS
======================= --}}
<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-2">
    <div class="card">
        <div class="text-sm text-slate-500">จำนวนเคสของฉัน</div>
        <div class="mt-2 text-3xl font-bold">
            {{ $stats['submitted_cases'] }}
        </div>
    </div>

    <div class="card">
        <div class="text-sm text-slate-500">เคสเดือนนี้</div>
        <div class="mt-2 text-3xl font-bold">
            {{ $stats['month_cases'] }}
        </div>
    </div>
</div>

{{-- =======================
    TABLE
======================= --}}
<div class="mt-6 calendar-wrap">
    <div class="card calendar-card">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-lg font-semibold">ปฏิทิน</h2>
        </div>

        <div id="calendar" class="shift-calendar"></div>
    </div>
</div>

<div class="mt-6 card">

    {{-- HEADER --}}
    <div class="mb-4">
        <h2 class="text-lg font-semibold">รายการล่าสุด</h2>
    </div>

    {{-- =======================
        FILTER
    ======================= --}}
    <form method="GET" class="mb-4 flex flex-wrap items-end gap-3">

        {{-- From Date --}}
        <div>
            <label class="text-sm text-slate-500">จากวันที่</label>
            <input 
                type="date" 
                name="from_date" 
                value="{{ request('from_date') }}" 
                class="input"
            >
        </div>

        {{-- To Date --}}
        <div>
            <label class="text-sm text-slate-500">ถึงวันที่</label>
            <input 
                type="date" 
                name="to_date" 
                value="{{ request('to_date') }}" 
                class="input"
            >
        </div>

        {{-- ✅ เฉพาะของฉัน --}}
        <div class="flex items-center gap-2 pt-5">
            <input 
                type="checkbox" 
                name="my_only" 
                value="1"
                {{ request('my_only') ? 'checked' : '' }}
                class="h-4 w-4"
            >
            <label class="text-sm text-slate-600">
                เฉพาะของฉัน
            </label>
        </div>

        {{-- BUTTON --}}
        <div class="flex gap-2 pt-5">
            <button type="submit" class="btn btn-primary">
                ค้นหา
            </button>

            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                ล้างค่า
            </a>
        </div>

    </form>

    {{-- =======================
        TABLE
    ======================= --}}
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>หมายเลขชันสูตรพลิกศพ</th>
                    <th>วันที่</th>
                    <th>แพทย์</th>
                    <th>ผู้ช่วยแพทย์</th>
                    <th>สถานีตำรวจ</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200">
                @forelse ($recentCases as $case)
                    <tr>
                        {{-- Scene No --}}
                        <td>
                            <a href="{{ route('scene-cases.show', $case) }}" class="font-medium text-blue-600">
                                {{ $case->scene_no }}
                            </a>
                        </td>

                        {{-- Date --}}
                        <td>
                            {{ optional($case->case_date)->format('d/m/Y') }}
                        </td>

                        {{-- Doctor --}}
                        <td>
                            {{ $case->doctor?->displayName() }}
                        </td>

                        {{-- Assistant --}}
                        <td>
                            {{ $case->assistant?->displayName() }}
                        </td>

                        {{-- Police Station --}}
                        <td>
                            {{ $case->policeStation?->name }}
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-center text-slate-500 py-6">
                            ยังไม่มีข้อมูล
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>



@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'th',
        initialView: 'dayGridMonth',
        height: 'auto',
        expandRows: true,
        events: '{{ route('shifts.events') }}',
        dayMaxEvents: 3,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        buttonText: {
            today: 'วันนี้'
        },
        // eventClick(info) {
        //     if (info.event.extendedProps.edit_url) {
        //         window.location.href = info.event.extendedProps.edit_url;
        //     }
        // },
       eventContent(arg) {
    const shiftType = arg.event.extendedProps.shift_type || '';
    const type = arg.event.extendedProps.short_type || '';
    const doctor = arg.event.extendedProps.doctor_short || '';
    const assistant = arg.event.extendedProps.assistant_short || '';

    const wrapper = document.createElement('div');

    if (shiftType === 'night') {
        wrapper.className = 'shift-event shift-event-night';
    } else if (shiftType === 'day') {
        wrapper.className = 'shift-event shift-event-day';
    } else {
        wrapper.className = 'shift-event';
    }

    const typeEl = document.createElement('div');
    typeEl.className = 'shift-event-type';
    typeEl.textContent = type;
    wrapper.appendChild(typeEl);

    if (doctor) {
        const doctorEl = document.createElement('div');
        doctorEl.className = 'shift-event-name';
        doctorEl.textContent = `แพทย์. ${doctor}`;
        wrapper.appendChild(doctorEl);
    }

    if (assistant) {
        const assistantEl = document.createElement('div');
        assistantEl.className = 'shift-event-name';
        assistantEl.textContent = `ผู้ช่วยแพทย์. ${assistant}`;
        wrapper.appendChild(assistantEl);
    }

    return { domNodes: [wrapper] };
},    eventOrder: function(a, b) {

        const order = { day: 1, night: 2 }; // กลางวันก่อนกลางคืน

        return order[a.extendedProps.shift_type] - order[b.extendedProps.shift_type];

    },
    });

    calendar.render();

    setTimeout(() => calendar.updateSize(), 100);
    window.addEventListener('load', () => calendar.updateSize());
    window.addEventListener('resize', () => calendar.updateSize());
});
</script>
@endpush