<div class="mt-4">
    <label class="label">Name</label>
    <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" class="input">
</div>
<div class="grid gap-4 md:grid-cols-2">

    <div class="flex items-center gap-2 pt-7">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true))>
        <label class="label">เปิดใช้งาน</label>
    </div>
</div>
 