@extends('layouts.app', ['title' => 'Reports'])

@section('content')
<div class="card">
    <form class="grid gap-3 md:grid-cols-4">
        <div>
            <label class="label">From Date</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="input">
        </div>
        <div>
            <label class="label">To Date</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="input">
        </div>
        <div>
            <label class="label">Status</label>
            <select name="status" class="input">
                <option value="">-- ทุกสถานะ --</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="submitted" @selected(request('status') === 'submitted')>Submitted</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">Filter</button>
        </div>
    </form>
</div>

<div class="mt-6 grid gap-4 md:grid-cols-3">
    <div class="card"><div class="text-sm text-slate-500">ทั้งหมด</div><div class="mt-2 text-3xl font-bold">{{ $summary['total'] }}</div></div>
    <div class="card"><div class="text-sm text-slate-500">Draft</div><div class="mt-2 text-3xl font-bold">{{ $summary['draft'] }}</div></div>
    <div class="card"><div class="text-sm text-slate-500">Submitted</div><div class="mt-2 text-3xl font-bold">{{ $summary['submitted'] }}</div></div>
</div>

<div class="mt-6 grid gap-6 xl:grid-cols-3">
    <div class="card xl:col-span-2">
        <h2 class="mb-4 text-lg font-semibold">รายการเคส</h2>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Scene No</th><th>วันที่</th><th>ผู้ปฏิบัติงาน</th><th>ประเภทที่แจ้ง</th><th>สถานะ</th></tr></thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($cases as $case)
                        <tr>
                            <td class="font-medium"><a href="{{ route('scene-cases.show', $case) }}" class="text-blue-600">{{ $case->scene_no }}</a></td>
                            <td>{{ optional($case->case_date)->format('d/m/Y') }}</td>
                            <td>{{ $case->doctor?->displayName() }} / {{ $case->assistant?->displayName() }}</td>
                            <td>{{ $case->notificationType?->name }}</td>
                            <td>{{ $case->status->label() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-slate-500">ไม่พบข้อมูลในช่วงวันที่ที่เลือก</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $cases->links() }}</div>
    </div>

    <div class="card">
        <h2 class="mb-4 text-lg font-semibold">สรุปตามประเภทที่แจ้ง</h2>
        <div class="space-y-3">
            @forelse ($byNotificationType as $row)
                <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3 py-2">
                    <span>{{ $row->notificationType?->name ?? 'ไม่ระบุ' }}</span>
                    <span class="font-semibold">{{ $row->total }}</span>
                </div>
            @empty
                <div class="text-sm text-slate-500">ยังไม่มีข้อมูล</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
