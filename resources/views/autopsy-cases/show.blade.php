@extends('layouts.app', ['title' => 'Scene Case Detail'])

@section('content')
<div class="grid gap-12 lg:grid-cols-12">
    <div class="card lg:col-span-2">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-xl font-semibold">{{ $autopsyCase->autopsy_no }}</h2>
        </div>
        <dl class="mt-6 grid gap-4 md:grid-cols-2">
            <div><dt class="text-slate-500">ชื่อผู้เสียชีวิต</dt><dd>{{ $autopsyCase->scene->deceased_name ?: '-' }}</dd></div>
            <div><dt class="text-slate-500">สถานีตำรวจ</dt><dd>{{ $autopsyCase->policeStation?->name }}</dd></div>
            <div><dt class="text-slate-500">แพทย์ผู้ผ่าพิสูจน์</dt><dd>{{ $autopsyCase->doctor?->displayName() }}</dd></div>
            <div><dt class="text-slate-500">วันที่ผ่าพิสูจน์</dt><dd>{{ optional($autopsyCase->autopsy_date)->format('d/m/Y') }}</dd></div>
            <div><dt class="text-slate-500">รูปแบบการผ่า</dt><dd>{{ $autopsyCase->autopsy_method == 'autopsy' ? 'ผ่า' : 'ไม่ผ่า' }}</dd></div>
            <div><dt class="text-slate-500">ผู้ช่วยผ่า</dt><dd>{{ $autopsyCase->assistant?->name ?: '-' }}</dd></div>
            <div><dt class="text-slate-500">ผู้ช่วยถ่ายภาพ</dt><dd>{{ $autopsyCase->photo?->name }}</dd></div>
            <div><dt class="text-slate-500">ส่งตรวจทางห้องปฎิบัติการ</dt><dd>{{ $autopsyCase->lab?->name }}</dd></div>
            <div class="md:col-span-2"><dt class="text-slate-500">หมายเหตุ</dt><dd>{{ $autopsyCase->remarks ?: '-' }}</dd></div>
        </dl>

        <div class="mt-6 flex flex-wrap gap-2">
            <a   href="{{ route('approve-autopsy-cases.index') }}" class="btn btn-secondary">กลับ</a>
        </div>
       </div>
   
    </div>

    </div>
</div>
@endsection
