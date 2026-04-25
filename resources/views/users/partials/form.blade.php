<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="label">ชื่อ login</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="input">
    </div>
    <div>
        <label class="label">ชื่อ-นามสกุล</label>
        <input type="text" name="full_name" value="{{ old('full_name', $user->full_name ?? '') }}" class="input">
    </div>
    <div>
        <label class="label">ชื่อแสดงในระบบ</label>
        <input type="text" name="username" value="{{ old('username', $user->username ?? '') }}" class="input">
    </div>
    <div>
        <label class="label">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="input">
    </div>
    <div>
        <label class="label">เบอร์โทร</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="input">
    </div>
    <div>
        <label class="label">ตำแหน่ง</label>
        <input type="text" name="position" value="{{ old('position', $user->position ?? '') }}" class="input">
    </div>
    <div>
        <label class="label">รหัสผ่าน {{ isset($user) ? '(เว้นว่างหากไม่เปลี่ยน)' : '' }}</label>
        <input type="password" name="password" class="input">
    </div>
    <div>
        <label class="label">ยืนยันรหัสผ่าน</label>
        <input type="password" name="password_confirmation" class="input">
    </div>
</div>

<div class="mt-4 grid gap-4 md:grid-cols-2">
    <div>
        <label class="label">บทบาท</label>
        <select name="role" class="input">
            <option value="">-- เลือกบทบาท --</option>
            @foreach ($roles as $role)
                <option value="{{ $role->name }}" @selected(old('role', isset($user) ? $user->roles->first()?->name : '') === $role->name)>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="flex items-center gap-2 pt-7">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true))>
        <label class="label">เปิดใช้งาน</label>
    </div>
</div>

<div class="mt-4">
    <label class="label">สิทธิ์ (ถ้าจำเป็น)</label>
    <div class="mt-2 grid gap-2 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($permissions as $permission)
            <label class="flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2">
                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                    @checked(in_array($permission->name, old('permissions', isset($user) ? $user->permissions->pluck('name')->all() : [])))>
                <span>{{ $permission->name }}</span>
            </label>
        @endforeach
    </div>
</div>
