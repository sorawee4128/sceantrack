@extends('layouts.app', ['title' => 'Create Shift'])

@section('content')
<div class="card">
    <form method="POST" action="{{ route('shifts.store') }}">
        @csrf
        @include('shifts.partials.form')
        <div class="mt-6 flex gap-2">
            <button class="btn btn-primary">บันทึก</button>
            <a href="{{ route('shifts.index') }}" class="btn btn-secondary">กลับ</a>
        </div>
    </form>
</div>
@endsection
