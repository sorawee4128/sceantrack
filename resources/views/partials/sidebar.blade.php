<div class="flex h-full flex-col">
    <div class="border-b border-slate-800 px-5 py-4">
        <div class="text-lg font-bold">Forensics</div>
        <div class="text-sm text-slate-400">Product</div>
    </div>

    <nav class="flex-1 space-y-1 px-3 py-4 text-sm">
        <a href="{{ route('dashboard') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">หน้าหลัก</a>

        @can('manage shifts')
            <a href="{{ route('shifts.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">ตารางเวรออกชันสูตรพลิกศพ</a>
        @endcan

        @canany(['manage scene cases', 'view own scene cases', 'submit scene cases'])
            <a href="{{ route('scene-cases.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">ลงข้อมูลการชันสูตรศพ</a>
        @endcanany

        @canany(['view all reports', 'view own reports'])
            <a href="{{ route('reports.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">สรุปรายการชันสูตรพลิกศพ</a>
        @endcanany

        @if(!auth()->user()->hasRole('doctor'))
            @can('manage autopsy cases')
                <a href="{{ route('autopsy-cases.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">สถานะรายงานการผ่าชันสูตรศพ</a>
            @endcan
        @endif
        
        @can('manage autopsy cases')
            <a href="{{ route('approve-autopsy-cases.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">ลงข้อมูลการผ่าชันสูตรศพ</a>
        @endcan

        @can('manage scene service fee')
        <a href="{{ route('scene-service-fee.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">คำนวนค่าตอบแทน</a>
        @endcan

        @can('manage users')
            <div class="pt-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Admin</div>
            <a href="{{ route('users.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">Users</a>
        @endcan

        @can('manage roles')
            <a href="{{ route('roles.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">Roles</a>
        @endcan

        @can('manage permissions')
            <a href="{{ route('permissions.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">Permissions</a>
        @endcan

        @can('manage master data')
            <div class="pt-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Master Data</div>
            <a href="{{ route('master-data.police-stations.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">Police Stations</a>
            <a href="{{ route('master-data.body-handlings.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">Body Handlings</a>
            <a href="{{ route('master-data.notification-types.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">Notification Types</a>
            <a href="{{ route('master-data.genders.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">Genders</a>
            <a href="{{ route('master-data.labs.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">Labs</a>
            <a href="{{ route('master-data.autopsy-assistants.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">Autopsy Assistants</a>
            <a href="{{ route('master-data.photo-assistants.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800">Photo Assistants</a>
        @endcan
    </nav>

    <div class="border-t border-slate-800 p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-secondary w-full justify-center">logout</button>
        </form>
    </div>
</div>
