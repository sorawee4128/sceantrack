@extends('layouts.app', ['title' => 'Edit Permission'])

@section('content')
<div class="card">
    <form method="POST" action="{{ route('permissions.update', $permission) }}">
        @csrf
        @method('PUT')
        @include('permissions.partials.form')
        <div class="mt-6 flex gap-2">
            <button class="btn btn-primary">บันทึก</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">กลับ</a>
        </div>
    </form>
</div>
@endsection
