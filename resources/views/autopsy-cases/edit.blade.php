@extends('layouts.app', ['title' => 'แก้ไขรายการข้อมูลการผ่าชันสูตรศพ'])

@section('content')
<div class="card">
    <form
        method="POST"
        enctype="multipart/form-data"
        action="{{ route('autopsy-cases.update', $autopsyCase) }}"
        x-data="{ submitting: false }"
        @submit="submitting = true"
    >
        @csrf
        @method('PUT')
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
                href="{{ route('approve-autopsy-cases.index') }}"
                class="btn btn-secondary"
                :class="{ 'pointer-events-none opacity-50': submitting }"
            >
                กลับ
            </a>
        </div>
    </form>
</div>
@endsection