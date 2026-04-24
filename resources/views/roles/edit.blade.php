@extends('layouts.app', ['title' => 'Edit Role'])

@section('content')
<div class="card">
    <form method="POST" action="{{ route('roles.update', $role) }}">
        @csrf
        @method('PUT')
        @include('roles.partials.form')
        <div class="mt-6 flex gap-2">
            <button class="btn btn-primary">บันทึก</button>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">กลับ</a>
        </div>
    </form>
</div>
@endsection
