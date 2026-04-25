@extends('layouts.app', ['title' => 'ผู้ใช้ระบบ'])

@section('content')
<div class="card">
    <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <form class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="ค้นหาผู้ใช้..." class="input md:w-80">
            <button class="btn btn-secondary">ค้นหา</button>
        </form>
        <a href="{{ route('users.create') }}" class="btn btn-primary">+ เพิ่มผู้ใช้งาน</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ชื่อ/อีเมล</th>
                    <th>ชื่อแสดงในระบบ</th>
                    <th>บทบาท</th>
                    <th>สถานะ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <div class="font-medium">{{ $user->displayName() }}</div>
                            <div class="text-slate-500">{{ $user->email }}</div>
                        </td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->getRoleNames()->join(', ') }}</td>
                        <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="text-right">
                            <div class="flex flex-wrap justify-end gap-2">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">ดู</a>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-secondary">แก้ไข</a>
                                <form method="POST" action="{{ route('users.toggle', $user) }}">
                                    @csrf
                                    <button class="btn {{ $user->is_active ? 'btn-danger' : 'btn-primary' }}">
                                        {{ $user->is_active ? 'ปิดใช้งาน' : 'เปิดใช้งาน' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if ($users->isEmpty())
                    <tr><td colspan="5" class="text-center text-slate-500">ไม่พบข้อมูลผู้ใช้งาน</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</div>
@endsection
