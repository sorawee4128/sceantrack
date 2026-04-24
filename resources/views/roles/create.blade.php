@extends('layouts.app', ['title' => 'Create Role'])

@section('content')
<div class="card">
    <form method="POST" action="{{ route('roles.store') }}">
        @csrf
        @include('roles.partials.form')
        <div class="mt-6 flex gap-2">
            <button class="btn btn-primary">บันทึก</button>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">กลับ</a>
        </div>
    </form>
</div>
@endsection
