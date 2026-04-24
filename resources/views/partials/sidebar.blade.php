@php

    $linkClass = 'flex items-center rounded-xl px-4 py-2.5 text-sm font-semibold transition';

    $inactiveClass = 'text-slate-400 hover:bg-white/5 hover:text-white';

    // ⭐ ตัวนี้คือ highlight ใหม่

    $activeClass = 'bg-gradient-to-r from-indigo-500 to-blue-500 text-white shadow-md';

    $sectionClass = 'mt-6 mb-2 px-4 text-xs font-bold uppercase tracking-widest text-slate-500';

@endphp

<div class="flex h-full flex-col bg-slate-950 text-slate-100">
    <div class="relative border-b border-white/10 px-6 py-5">
        {{-- LOGO มุมขวาบน --}}
<img src="{{ asset('images/logo.webp') }}"

     class="absolute top-4 right-4 h-10 w-10 object-contain opacity-90"
style="height: 70%;width: 25%;"
     alt="logo">
        <div class="text-xl font-bold text-white">Forensics</div>
        <div class="mt-1 text-sm text-slate-400">Product</div>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-5 text-sm">
        <a href="{{ route('dashboard') }}"
           class="{{ $linkClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
            หน้าหลัก
        </a>

        @can('manage shifts')
            <a href="{{ route('shifts.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('shifts.*') ? $activeClass : $inactiveClass }}">
                ตารางเวรออกชันสูตรพลิกศพ
            </a>
        @endcan

        @canany(['manage scene cases', 'view own scene cases', 'submit scene cases'])
            <a href="{{ route('scene-cases.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('scene-cases.*') ? $activeClass : $inactiveClass }}">
                ลงข้อมูลการชันสูตรศพ
            </a>
        @endcanany

        @canany(['view all reports', 'view own reports'])
            <a href="{{ route('reports.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('reports.*') ? $activeClass : $inactiveClass }}">
                สรุปรายการชันสูตรพลิกศพ
            </a>

            <a href="{{ route('autopsy-reports.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('autopsy-reports.*') ? $activeClass : $inactiveClass }}">
                สรุปรายการข้อมูลการผ่าชันสูตรศพ
            </a>

        @endcanany

        @if(!auth()->user()->hasRole('doctor'))
            @can('manage autopsy cases')
                <a href="{{ route('autopsy-cases.index') }}"
                   class="{{ $linkClass }} {{ request()->routeIs('autopsy-cases.*') ? $activeClass : $inactiveClass }}">
                    สถานะรายงานการผ่าชันสูตรศพ
                </a>
            @endcan
        @endif

        @can('manage autopsy cases')
            <a href="{{ route('approve-autopsy-cases.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('approve-autopsy-cases.*') ? $activeClass : $inactiveClass }}">
                ลงข้อมูลการผ่าชันสูตรศพ
            </a>
        @endcan

        @can('manage scene service fee')
            <a href="{{ route('scene-service-fee.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('scene-service-fee.*') ? $activeClass : $inactiveClass }}">
                คำนวณค่าตอบแทน
            </a>
        @endcan

        @can('manage users')
            <div class="{{ $sectionClass }}">Admin</div>
            <a href="{{ route('users.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('users.*') ? $activeClass : $inactiveClass }}">
                Users
            </a>
        @endcan

        @can('manage roles')
            <a href="{{ route('roles.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('roles.*') ? $activeClass : $inactiveClass }}">
                Roles
            </a>
        @endcan

        @can('manage permissions')
            <a href="{{ route('permissions.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('permissions.*') ? $activeClass : $inactiveClass }}">
                Permissions
            </a>
        @endcan

        @can('manage master data')
            <div class="{{ $sectionClass }}">Master Data</div>

            <a href="{{ route('master-data.police-stations.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('master-data.police-stations.*') ? $activeClass : $inactiveClass }}">
                Police Stations
            </a>

            <a href="{{ route('master-data.body-handlings.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('master-data.body-handlings.*') ? $activeClass : $inactiveClass }}">
                Body Handlings
            </a>

            <a href="{{ route('master-data.notification-types.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('master-data.notification-types.*') ? $activeClass : $inactiveClass }}">
                Notification Types
            </a>

            <a href="{{ route('master-data.genders.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('master-data.genders.*') ? $activeClass : $inactiveClass }}">
                Genders
            </a>

            <a href="{{ route('master-data.labs.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('master-data.labs.*') ? $activeClass : $inactiveClass }}">
                Labs
            </a>

            <a href="{{ route('master-data.autopsy-assistants.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('master-data.autopsy-assistants.*') ? $activeClass : $inactiveClass }}">
                Autopsy Assistants
            </a>

            <a href="{{ route('master-data.photo-assistants.index') }}"
               class="{{ $linkClass }} {{ request()->routeIs('master-data.photo-assistants.*') ? $activeClass : $inactiveClass }}">
                Photo Assistants
            </a>
        @endcan
    </nav>

    <div class="border-t border-white/10 p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-white/10 hover:text-white">
                Logout
            </button>
        </form>
    </div>
</div>