@extends('layouts.app', ['title' => 'Scene Case Detail'])

@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <div class="card lg:col-span-2">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-xl font-semibold">{{ $sceneCase->scene_no }}</h2>
            <span class="badge {{ $sceneCase->status->value === 'submitted' ? 'badge-submitted' : 'badge-draft' }}">
                {{ $sceneCase->status->label() }}
            </span>
        </div>

        <dl class="mt-6 grid gap-4 md:grid-cols-2">
            <div><dt class="text-slate-500">วันที่ชันสูตรพลิกศพ</dt><dd>{{ optional($sceneCase->case_date)->format('d/m/Y') }}</dd></div>
            <div><dt class="text-slate-500">กะเวร</dt><dd>{{ $sceneCase->shift?->shift_date?->format('d/m/Y') }} - {{ $sceneCase->shift?->shift_type?->label() }}</dd></div>
            <div><dt class="text-slate-500">Doctor</dt><dd>{{ $sceneCase->doctor?->displayName() }}</dd></div>
            <div><dt class="text-slate-500">Assistant</dt><dd>{{ $sceneCase->assistant?->displayName() }}</dd></div>
            <div><dt class="text-slate-500">สถานีตำรวจ</dt><dd>{{ $sceneCase->policeStation?->name }}</dd></div>
            <div><dt class="text-slate-500">ผู้เสียชีวิต</dt><dd>{{ $sceneCase->deceased_name ?: '-' }}</dd></div>
            <div><dt class="text-slate-500">เพศ / อายุ</dt><dd>{{ $sceneCase->gender?->name ?: '-' }} / {{ $sceneCase->age ?? '-' }}</dd></div>
            <div><dt class="text-slate-500">การจัดการศพ</dt><dd>{{ $sceneCase->bodyHandling?->name }}</dd></div>
            <div><dt class="text-slate-500">ประเภทที่แจ้ง</dt><dd>{{ $sceneCase->notificationType?->name }}</dd></div>
            <div><dt class="text-slate-500">เวลารับแจ้ง</dt><dd>{{ $sceneCase->notified_time ?: '-' }}</dd></div>
            <div><dt class="text-slate-500">เวลาที่ชันสูตร</dt><dd>{{ $sceneCase->arrival_time ?: '-' }}</dd></div>
            <div class="md:col-span-2"><dt class="text-slate-500">รายละเอียดเคส</dt><dd>{{ $sceneCase->case_description ?: '-' }}</dd></div>
            <div class="md:col-span-2"><dt class="text-slate-500">หมายเหตุ</dt><dd>{{ $sceneCase->remarks ?: '-' }}</dd></div>
        </dl>

        <div class="mt-6 flex flex-wrap gap-2">
            @can('update', $sceneCase)
                <a href="{{ route('scene-cases.edit', $sceneCase) }}" class="btn btn-secondary">แก้ไข</a>
            @endcan
            @can('delete', $sceneCase)
                <form method="POST" action="{{ route('scene-cases.destroy', $sceneCase) }}" onsubmit="return confirm('ยืนยันการลบ?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">ลบ</button>
                </form>
            @endcan
        </div>
    </div>

    <div class="card">
        <h3 class="text-lg font-semibold">Gallery</h3>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
            @forelse ($sceneCase->photos as $photo)
                <a href="{{ $photo->url() }}" target="_blank" class="overflow-hidden rounded-2xl border border-slate-200">
                    <img src="{{ $photo->url() }}" alt="{{ $photo->file_name }}" class="h-40 w-full object-cover">
                </a>
            @empty
                <div class="text-sm text-slate-500">ยังไม่มีรูปภาพ</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
