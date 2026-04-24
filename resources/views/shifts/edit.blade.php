@extends('layouts.app', ['title' => 'แก้ไขตารางเวรออกชันสูตรพลิกศพ'])

@section('content')
<div class="card">
    <form method="POST" action="{{ route('shifts.update', $shift) }}">
        @csrf
        @method('PUT')
        @include('shifts.partials.form')
        <div class="mt-6 flex gap-2">
            <button class="btn btn-primary">บันทึก</button>
            <a href="{{ route('shifts.index') }}" class="btn btn-secondary">กลับ</a>
        </div>
    </form>
</div>
@endsection
