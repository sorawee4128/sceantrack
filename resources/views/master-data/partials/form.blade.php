<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="label">รหัส</label>
        <input type="text" name="code" value="{{ old('code', $item->code ?? '') }}" class="input">
    </div>
    <div class="flex items-center gap-2 pt-7">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true))>
        <label class="label">เปิดใช้งาน</label>
    </div>
</div>

<div class="mt-4">
    <label class="label">ชื่อ</label>
    <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" class="input">
</div>

<div class="mt-4">
    <label class="label">คำอธิบาย</label>
    <textarea name="description" rows="4" class="input">{{ old('description', $item->description ?? '') }}</textarea>
</div>
