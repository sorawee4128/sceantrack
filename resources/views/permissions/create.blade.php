@extends('layouts.app', ['title' => 'สร้างสิทธิ์'])

@section('content')
<div class="card">
    <form method="POST" action="{{ route('permissions.store') }}">
        @csrf
        @include('permissions.partials.form')
        <div class="mt-6 flex gap-2">
            <button class="btn btn-primary">บันทึก</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">กลับ</a>
        </div>
    </form>
</div>
@endsection
