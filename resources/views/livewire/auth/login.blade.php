<x-layouts::auth :title="'เข้าสู่ระบบ'">

    <div class="text-center mb-6">

        <div class="logo-box">
            <img src="{{ asset('images/logo.png') }}">
        </div>

        <h1 class="text-xl font-semibold text-slate-900">Forensics</h1>
        <p class="text-sm text-slate-500 mt-1">
            ระบบจัดการตารางเวรและข้อมูลการชันสูตรศพ
        </p>
    </div>

    <x-auth-session-status class="mb-4 text-center text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
        @csrf

        <flux:input
            name="email"
            label="อีเมล"
            :value="old('email')"
            type="email"
            required
            autofocus
            placeholder="email@example.com"
        />

        <div class="relative">
            <flux:input
                name="password"
                label="รหัสผ่าน"
                type="password"
                required
                placeholder="รหัสผ่าน"
                viewable
            />

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="absolute top-0 right-0 text-sm text-slate-400 hover:text-slate-700">
                    ลืมรหัสผ่าน?
                </a>
            @endif
        </div>

        <flux:checkbox name="remember" label="จดจำฉัน" />

        <button type="submit" class="w-full text-white btn-login">
            เข้าสู่ระบบ
        </button>
    </form>

</x-layouts::auth>