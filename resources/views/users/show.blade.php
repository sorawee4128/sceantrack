@extends('layouts.app', ['title' => 'View User'])

@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <div class="card lg:col-span-2">
        <h2 class="text-lg font-semibold">ข้อมูลผู้ใช้งาน</h2>
        <dl class="mt-4 grid gap-4 md:grid-cols-2">
            <div><dt class="text-slate-500">ชื่อ-นามสกุล</dt><dd class="font-medium">{{ $user->displayName() }}</dd></div>
            <div><dt class="text-slate-500">Username</dt><dd class="font-medium">{{ $user->username }}</dd></div>
            <div><dt class="text-slate-500">Email</dt><dd class="font-medium">{{ $user->email }}</dd></div>
            <div><dt class="text-slate-500">โทรศัพท์</dt><dd class="font-medium">{{ $user->phone ?: '-' }}</dd></div>
            <div><dt class="text-slate-500">ตำแหน่ง</dt><dd class="font-medium">{{ $user->position ?: '-' }}</dd></div>
            <div><dt class="text-slate-500">สถานะ</dt><dd class="font-medium">{{ $user->is_active ? 'Active' : 'Inactive' }}</dd></div>
        </dl>
    </div>
    <div class="card">
        <h2 class="text-lg font-semibold">สิทธิ์</h2>
        <div class="mt-4">
            <div class="mb-2 text-sm text-slate-500">Roles</div>
            <div class="flex flex-wrap gap-2">
                @foreach ($user->roles as $role)
                    <span class="badge bg-blue-100 text-blue-700">{{ $role->name }}</span>
                @endforeach
            </div>
        </div>
        <div class="mt-4">
            <div class="mb-2 text-sm text-slate-500">Direct Permissions</div>
            <div class="flex flex-wrap gap-2">
                @forelse ($user->permissions as $permission)
                    <span class="badge bg-slate-100 text-slate-700">{{ $permission->name }}</span>
                @empty
                    <span class="text-sm text-slate-500">ไม่มี</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
