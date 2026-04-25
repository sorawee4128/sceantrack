@extends('layouts.app', ['title' => 'ลงข้อมูลการชันสูตรศพ'])

@section('content')
<div class="card">
    <form
        method="POST"
        enctype="multipart/form-data"
        action="{{ route('scene-cases.store') }}"
    >
        @csrf

        @include('scene-cases.partials.form')

        <div class="mt-6 flex flex-wrap gap-3">
            <button
                type="submit"
                name="action"
                value="draft"
                class="rounded-xl border border-slate-200 bg-slate-100 px-5 py-2 font-semibold text-slate-600 hover:bg-slate-200"
            >
                บันทึกฉบับร่าง
            </button>

            <button
                type="submit"
                name="action"
                value="submit"
                class="rounded-xl bg-blue-500 px-5 py-2 font-semibold text-white shadow hover:bg-blue-600"
            >
                บันทึก
            </button>

            <a
                href="{{ route('scene-cases.index') }}"
                class="rounded-xl border border-slate-200 bg-white px-5 py-2 font-semibold text-slate-600 hover:bg-slate-50"
            >
                กลับ
            </a>
        </div>
    </form>
</div>
@endsection