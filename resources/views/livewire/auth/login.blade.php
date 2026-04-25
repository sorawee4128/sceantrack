<x-layouts::auth :title="'เข้าสู่ระบบ'">
    <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white/95 p-8 shadow-lg">
        <div class="mb-7 text-center">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="Forensics Logo"
                class="mx-auto mb-4 h-28 w-28 object-contain"
            >

            <h1 class="text-2xl font-bold text-slate-900">
                Forensics
            </h1>

            <p class="mt-2 text-sm font-medium text-slate-500">
                ระบบจัดการตารางเวรและข้อมูลการชันสูตรศพ
            </p>
        </div>

        <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

            <flux:input
                name="email"
                label="อีเมล"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <div class="relative">
                <flux:input
                    name="password"
                    label="รหัสผ่าน"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="รหัสผ่าน"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link
                        class="absolute top-0 end-0 text-sm font-semibold text-slate-500 hover:text-blue-600"
                        :href="route('password.request')"
                        wire:navigate
                    >
                        ลืมรหัสผ่าน?
                    </flux:link>
                @endif
            </div>

            <flux:checkbox
                name="remember"
                label="จดจำฉัน"
                :checked="old('remember')"
            />

            <flux:button
                variant="primary"
                type="submit"
                class="w-full rounded-xl bg-slate-900 py-3 font-semibold text-white shadow-md hover:bg-slate-800"
                data-test="login-button"
            >
                เข้าสู่ระบบ
            </flux:button>
        </form>
    </div>
</x-layouts::auth>