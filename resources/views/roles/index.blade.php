@extends('layouts.app', ['title' => 'Roles'])

@section('content')
<div class="card">
    <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <form class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="ค้นหา role..." class="input md:w-80">
            <button class="btn btn-secondary">ค้นหา</button>
        </form>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">+ เพิ่ม Role</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Name</th><th>Permissions</th><th></th></tr></thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($roles as $role)
                    <tr>
                        <td class="font-medium">{{ $role->name }}</td>
                        <td>{{ $role->permissions->pluck('name')->join(', ') }}</td>
                        <td class="text-right"><a href="{{ route('roles.edit', $role) }}" class="btn btn-secondary">แก้ไข</a></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-slate-500">ไม่พบข้อมูล</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $roles->links() }}</div>
</div>
@endsection
