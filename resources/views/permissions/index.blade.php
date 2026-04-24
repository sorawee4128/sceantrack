@extends('layouts.app', ['title' => 'Permissions'])

@section('content')
<div class="card">
    <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <form class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="ค้นหา permission..." class="input md:w-80">
            <button class="btn btn-secondary">ค้นหา</button>
        </form>
        <a href="{{ route('permissions.create') }}" class="btn btn-primary">+ เพิ่ม Permission</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Name</th><th></th></tr></thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($permissions as $permission)
                    <tr>
                        <td class="font-medium">{{ $permission->name }}</td>
                        <td class="text-right"><a href="{{ route('permissions.edit', $permission) }}" class="btn btn-secondary">แก้ไข</a></td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-center text-slate-500">ไม่พบข้อมูล</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $permissions->links() }}</div>
</div>
@endsection
