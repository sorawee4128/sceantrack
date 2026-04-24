@extends('layouts.app', ['title' => $title])

@section('content')
<div class="card">
    <form method="POST" action="{{ route($routePrefix.'.store') }}">
        @csrf
        @include('master-data.partials.form_2')
        <div class="mt-6 flex gap-2">
            <button class="btn btn-primary">บันทึก</button>
            <a href="{{ route($routePrefix.'.index') }}" class="btn btn-secondary">กลับ</a>
        </div>
    </form>
</div>
@endsection
