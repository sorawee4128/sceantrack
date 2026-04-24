@extends('layouts.app', ['title' => 'Scene Service Fee'])

@section('content')

<div class="mt-6 card">
    <div class="mb-4">
        <h2 class="text-lg font-semibold">คำนวนค่าตอบแทน</h2>
    </div>

    <form method="GET" class="mb-4 flex flex-wrap items-end gap-3">
        <div>
            <label for="month" class="mb-2 block text-sm font-semibold text-slate-700">ช่วงเวลา</label>
            <input
                type="month"
                name="month"
                id="month"
                value="{{ request('month') }}"
                class="input w-full"
            >
        </div>

        <div class="flex items-center gap-2 pt-5">
            <input 
                type="checkbox" 
                name="my_only" 
                value="1"
                {{ request('my_only') ? 'checked' : '' }}
                class="h-4 w-4"
            >
            <label class="text-sm text-slate-600">
                เฉพาะของฉัน
            </label>
        </div>

        {{-- BUTTON --}}
        <div class="flex gap-2 pt-5">
            <button type="submit" class="btn btn-primary">
                Filter
            </button>

            <a href="{{ route('scene-service-fee.index') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>

    </form>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ชื่อ</th>
                    <th>มอบให้ญาติ</th>
                    <th>ส่งตรวจเป็นเคส F</th>
                    <th>ส่งตรวจเป็นเคส O</th>
                    <th>ส่งตรวจเป็นเคส I</th>
                    <th>รวม</th>
                    <th>ค่าตอบแทน</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200">
                @forelse ($targets as $user)
                    <tr>
                        <td>
                            {{ $user->displayName() }}
                        </td>

                        <td>
                            {{ $user->n }}
                        </td>
                         <td>
                            {{ $user->f }}
                        </td>
                        <td>
                            {{ $user->o }}
                        </td>
                        <td>
                            {{ $user->i }}
                        </td>
                        <td>
                            {{ $user->sum }}
                        </td>
                        <td>
                            {{ $user->fee }}
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-center text-slate-500 py-6">
                            ยังไม่มีข้อมูล
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection