@extends('layouts.app', ['title' => 'ข้อมูลการชันสูตรศพ'])

@section('content')
<div class="card">
    <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <form class="grid gap-2 md:grid-cols-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="ค้นหาเลขซีน / ชื่อ / สถานที่" class="input">
            <!-- <select name="status" class="input">
                <option value="">-- ทุกสถานะ --</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="submitted" @selected(request('status') === 'submitted')>Submitted</option>
            </select> -->
            <button class="btn btn-secondary">ค้นหา</button>
        </form>
        @if(!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('doctor'))
        <a href="{{ route('scene-cases.create') }}" class="btn btn-primary">+ ลงข้อมูลการชันสูตรศพ</a>
        @endif
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>หมายเลขชันสูตรศพ</th>
                    <th>วันที่</th>
                    <th>ชื่อผู้เสียชีวิต</th>
                    <th>สถานีตำรวจ</th>
                    <th>การจัดการศพ</th>
                    <th>แพทย์ / ผู้ช่วยแพทย์</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($cases as $case)
                    <tr>
                        <td class="font-medium">{{ $case->scene_no }}</td>
                        <td>{{ optional($case->case_date)->format('d/m/Y') }}</td>
                        <td>{{ $case->deceased_name }}</td>
                        <td>{{ $case->policeStation?->name }}</td>
                        <td>{{ $case->bodyHandling?->name }}</td>
                        <td>
                            <div>- แพทย์. {{ $case->doctor?->displayName() }}</div>
                            <div>- ผู้ช่วยแพทย์. {{ $case->assistant?->displayName() }}</div>
                        </td>
                        <td class="text-right">
                            <div class="flex flex-wrap justify-end gap-2">
                                <a href="{{ route('scene-cases.show', $case) }}" class="btn btn-secondary">ดู</a>
                                @can('update', $case)
                                    <a href="{{ route('scene-cases.edit', $case) }}" class="btn btn-secondary">แก้ไข</a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-slate-500">ไม่พบข้อมูล</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $cases->links() }}</div>
</div>
@endsection
