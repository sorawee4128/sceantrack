<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Scene Case System' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-900">
<div x-data="{ open: false }" class="min-h-screen lg:flex">
    <aside :class="open ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-40 w-72 transform border-r border-slate-200 bg-slate-900 text-slate-100 transition lg:static lg:translate-x-0">
        @include('partials.sidebar')
    </aside>

    <div class="flex-1">
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button @click="open = !open" class="rounded-xl border border-slate-200 p-2 lg:hidden">
                        ☰
                    </button>
                    <div>
                        <p class="text-sm text-slate-500">ระบบจัดการตารางเวรและข้อมูลการชันสูตรศพ</p>
                        <h1 class="text-lg font-semibold" style="font-size: 20px;">{{ $title ?? 'หน้าหลัก' }}</h1>
                    </div>
                </div>
                <div class="text-right text-sm">
                    <div class="font-medium" style="font-size: 20px;">{{ auth()->user()->displayName() }}</div>
                    <div class="text-slate-500" style="font-size: 20px;">ตำแหน่ง {{ auth()->user()->position }}</div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <x-flash />
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
