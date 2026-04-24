@extends('layouts.app', ['title' => 'แก้ไขข้อมูลการชันสูตรศพ'])

@section('content')
<div class="card">
    <form
        method="POST"
        enctype="multipart/form-data"
        action="{{ route('scene-cases.update', $sceneCase) }}"
        x-data="{ submitting: false }"
        @submit="submitting = true"
    >
        @csrf
        @method('PUT')
        <input type="hidden" name="action" value="submit">

        @include('scene-cases.partials.form')

        <div class="mt-6 flex flex-wrap gap-2">
            <button
                type="submit"
                class="btn btn-primary"
                :disabled="submitting"
                x-text="submitting ? 'กำลังบันทึก...' : 'บันทึก'"
            ></button>

            <a
                href="{{ route('scene-cases.index') }}"
                class="btn btn-secondary"
                :class="{ 'pointer-events-none opacity-50': submitting }"
            >
                กลับ
            </a>
        </div>
    </form>
</div>
@endsection