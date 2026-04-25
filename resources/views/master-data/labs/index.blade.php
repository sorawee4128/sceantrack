@extends('layouts.app', ['title' => $title])

@section('content')
<div class="card">
    <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <form class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="ค้นหา..." class="input md:w-80">
            <button class="btn btn-secondary">ค้นหา</button>
        </form>
        <a href="{{ route($routePrefix.'.create') }}" class="btn btn-primary">+ เพิ่มรายการ</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ชื่อ</th>
                    <th>สถานะ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($items as $item)
                    <tr>
                        <td class="font-medium">{{ $item->name }}</td>
                        <td>{{ $item->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route($routePrefix.'.edit', $item) }}" class="btn btn-secondary">แก้ไข</a>
                                <form method="POST" action="{{ route($routePrefix.'.destroy', $item) }}" onsubmit="return confirm('ยืนยันการลบ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">ลบ/ปิดการใช้งาน</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-slate-500">ไม่พบข้อมูล</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection
