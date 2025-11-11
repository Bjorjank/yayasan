{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
    {{-- Status sesi (mis. "password reset link sent") --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" x-data="{ showPwd:false }" class="space-y-6">
        @csrf

        {{-- EMAIL --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative mt-1">
                {{-- Icon kiri --}}
                <span class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                    <x-heroicon-o-envelope class="w-5 h-5 text-gray-400" />
                </span>

                <x-text-input
                    id="email"
                    name="email"
                    type="email"
                    :value="old('email')"
                    placeholder="email@domain.com"
                    required
                    autofocus
                    autocomplete="username"
                    class="block w-full h-11 pl-11 pr-4" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- PASSWORD --}}
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative mt-1">
                {{-- Icon kiri --}}
                <span class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                    <x-heroicon-o-lock-closed class="w-5 h-5 text-gray-400" />
                </span>

                <x-text-input
                    id="password"
                    name="password"
                    x-bind:type="showPwd ? 'text' : 'password'"
                    placeholder="Masukkan password"
                    required
                    autocomplete="current-password"
                    class="block w-full h-11 pl-11 pr-11" />

                {{-- Toggle kanan --}}
                <button type="button"
                    @click="showPwd = !showPwd"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                    aria-label="Tampilkan/sembunyikan password">
                    <template x-if="!showPwd">
                        <x-heroicon-o-eye class="w-5 h-5" />
                    </template>
                    <template x-if="showPwd">
                        <x-heroicon-o-eye-slash class="w-5 h-5" />
                    </template>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- REMEMBER + FORGOT --}}
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 select-none">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-brand-blue shadow-sm focus:ring-brand-blue"
                    name="remember">
                <span class="text-sm text-gray-600">{{ __('Ingatkan Saya') }}</span>
            </label>

            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}"
                class="text-sm text-brand-blue hover:underline">
                {{ __('Lupa Password Kamu?') }}
            </a>
            @endif
        </div>

        {{-- SUBMIT --}}
        <div class="flex items-center justify-end">
            <x-primary-button class="ms-3 inline-flex items-center">
                <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 mr-2" />
                {{ __('Masuk') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>