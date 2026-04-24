@extends('layouts.app', ['title' => 'คำนวนค่าตอบแทน'])

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
                ค้นหา
            </button>

            <a href="{{ route('scene-service-fee.index') }}" class="btn btn-secondary">
                ล้างค่า
            </a>
        </div>

    </form>

<div class="flex justify-end gap-2 mb-3">

    @if(auth()->user()->hasRole('doctor') || auth()->user()->hasRole('admin'))
        <a href="{{ route('scene-service-fee.pdf', [
                'role' => 'doctor',
                'month' => request('month')
            ]) }}"
            class="btn btn-secondary"
            target="_blank">
            พิมพ์ PDF แพทย์
        </a>
    @endif

    @if(auth()->user()->hasRole('assistant') || auth()->user()->hasRole('admin'))
        <a href="{{ route('scene-service-fee.pdf', [
                'role' => 'assistant',
                'month' => request('month')
            ]) }}"
            class="btn btn-secondary"
            target="_blank">
            พิมพ์ PDF ผู้ช่วยแพทย์
        </a>
    @endif
</div>

    <div class="mt-2 table-wrap">
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
            <tfoot>
                <tr class="border-t-4 border-slate-900 bg-white text-lg font-bold text-red-500">
                    <td class="px-4 py-4">ยอดรวม</td>
                    <td class="px-4 py-4">{{ $totals['n'] }}</td>
                    <td class="px-4 py-4">{{ $totals['f'] }}</td>
                    <td class="px-4 py-4">{{ $totals['o'] }}</td>
                    <td class="px-4 py-4">{{ $totals['i'] }}</td>
                    <td class="px-4 py-4">{{ $totals['sum'] }}</td>
                    <td class="px-4 py-4">{{ number_format($totals['fee']) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>

@endsection