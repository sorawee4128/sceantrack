@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')

{{-- =======================
    STATS
======================= --}}
<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-2">
    <div class="card">
        <div class="text-sm text-slate-500">Scene Case Submitted</div>
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
            <label class="text-sm text-slate-500">From Date</label>
            <input 
                type="date" 
                name="from_date" 
                value="{{ request('from_date') }}" 
                class="input"
            >
        </div>

        {{-- To Date --}}
        <div>
            <label class="text-sm text-slate-500">To Date</label>
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
                Filter
            </button>

            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                Reset
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
                    <th>Scene No</th>
                    <th>วันที่</th>
                    <th>Doctor</th>
                    <th>Assistant</th>
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