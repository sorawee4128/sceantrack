@extends('layouts.app', ['title' => 'สร้างรายการข้อมูลการผ่าชันสูตรศพ'])

@section('content')
<div class="card">
    <form
        method="POST"
        enctype="multipart/form-data"
        action="{{ route('autopsy-cases.store') }}"
        x-data="{ submitting: false }"
        @submit="submitting = true"
    >
        @csrf
        <input type="hidden" name="action" value="submit">

        @include('autopsy-cases.partials.form')

        <div class="mt-6 flex flex-wrap gap-2">
            <button
                type="submit"
                class="btn btn-primary"
                :disabled="submitting"
                x-text="submitting ? 'กำลังบันทึก...' : 'บันทึก'"
            ></button>

            <a
                href="{{ route('autopsy-cases.index') }}"
                class="btn btn-secondary"
                :class="{ 'pointer-events-none opacity-50': submitting }"
            >
                กลับ
            </a>
        </div>
    </form>
</div>
@endsection