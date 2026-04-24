@extends('layouts.app', ['title' => 'Pending Autopsy Cases'])

@section('content')
<div class="card">
    <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <form class="grid gap-2 md:grid-cols-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="ค้นหาเลขซีน / ชื่อ / สถานที่" class="input">
            <button class="btn btn-secondary">ค้นหา</button>
        </form>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Scene No</th>
                    <th>วันที่</th>
                    <th>ชื่อผู้เสียชีวิต</th>
                    <th>สถานีตำรวจ</th>
                    <th>การจัดการศพ</th>
                    <th>Doctor / Assistant</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($cases as $case)
                    <tr>
                        <td class="font-medium">{{ $case->scene_no }}</td>
                        <td>{{ optional($case->case_date)->format('d/m/Y') }}</td>
                        <td>{{ $case->deceased_name }}</td>
                        <td>{{ $case->policeStation->name }}</td>
                        <td>{{ $case->bodyHandling->name }}</td>
                        <td>
                            <div>Dr. {{ $case->doctor?->displayName() }}</div>
                            <div>Asst. {{ $case->assistant?->displayName() }}</div>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('scene-cases.autopsy-cases.create', $case->id) }}" class="btn btn-secondary">
                                คลิกเพื่อใส่ข้อมูล
                            </a>
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
