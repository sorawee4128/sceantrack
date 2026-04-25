<div>
    <label class="label">ชื่อบทบาท</label>
    <input type="text" name="name" value="{{ old('name', $role->name ?? '') }}" class="input">
</div>

<div class="mt-4">
    <label class="label">สิทธิ์</label>
    <div class="mt-2 grid gap-2 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($permissions as $permission)
            <label class="flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2">
                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                    @checked(in_array($permission->name, old('permissions', isset($role) ? $role->permissions->pluck('name')->all() : [])))>
                <span>{{ $permission->name }}</span>
            </label>
        @endforeach
    </div>
</div>
