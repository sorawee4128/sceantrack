@extends('layouts.app', ['title' => 'Edit User'])

@section('content')
<div class="card">
    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')
        @include('users.partials.form')
        <div class="mt-6 flex gap-2">
            <button class="btn btn-primary">บันทึก</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">กลับ</a>
        </div>
    </form>
</div>
@endsection
