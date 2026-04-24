@extends('layouts.app', ['title' => 'สถานะรายงานการผ่าชันสูตรศพ'])

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="card">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 pb-4">
            <div>
                <p class="text-sm font-medium text-slate-500">เลขรายงาน</p>
                <h2 class="mt-1 text-2xl font-bold text-slate-900">
                    {{ $autopsyCase->autopsy_no }}
                </h2>
            </div>

            <a href="{{ route('approve-autopsy-cases.index') }}" class="btn btn-secondary">
                กลับ
            </a>
        </div>

        <dl class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            <div class="p-4">
                <dt class="text-sm font-medium text-slate-500">ชื่อผู้เสียชีวิต</dt>
                <dd class="mt-1 font-semibold text-slate-900">{{ $autopsyCase->scene?->deceased_name ?: '-' }}</dd>
            </div>

            <div class="p-4">
                <dt class="text-sm font-medium text-slate-500">สถานีตำรวจ</dt>
                <dd class="mt-1 font-semibold text-slate-900">{{ $autopsyCase->policeStation?->name ?: '-' }}</dd>
            </div>

            <div class="p-4">
                <dt class="text-sm font-medium text-slate-500">วันที่ผ่าพิสูจน์</dt>
                <dd class="mt-1 font-semibold text-slate-900">{{ optional($autopsyCase->autopsy_date)->format('d/m/Y') ?: '-' }}</dd>
            </div>

            <div class="p-4">
                <dt class="text-sm font-medium text-slate-500">แพทย์ผู้ผ่าพิสูจน์</dt>
                <dd class="mt-1 font-semibold text-slate-900">{{ $autopsyCase->doctor?->displayName() ?: '-' }}</dd>
            </div>

            <div class="p-4">
                <dt class="text-sm font-medium text-slate-500">รูปแบบการผ่า</dt>
                <dd class="mt-1 font-semibold text-slate-900">{{ $autopsyCase->autopsy_method == 'autopsy' ? 'ผ่า' : 'ไม่ผ่า' }}</dd>
            </div>

            <div class="p-4">
                <dt class="text-sm font-medium text-slate-500">ผู้ช่วยผ่า</dt>
                <dd class="mt-1 font-semibold text-slate-900">{{ $autopsyCase->assistant?->name ?: '-' }}</dd>
            </div>

            <div class="p-4">
                <dt class="text-sm font-medium text-slate-500">ผู้ช่วยถ่ายภาพ</dt>
                <dd class="mt-1 font-semibold text-slate-900">{{ $autopsyCase->photo?->name ?: '-' }}</dd>
            </div>

            <div class="p-4">
                <dt class="text-sm font-medium text-slate-500">ส่งตรวจทางห้องปฏิบัติการ</dt>
                <dd class="mt-1 font-semibold text-slate-900">{{ $autopsyCase->lab?->name ?: '-' }}</dd>
            </div>

            <div class="p-4 md:col-span-2 xl:col-span-3">
                <dt class="text-sm font-medium text-slate-500">หมายเหตุ</dt>
                <dd class="mt-1 font-semibold text-slate-900">{{ $autopsyCase->remarks ?: '-' }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection