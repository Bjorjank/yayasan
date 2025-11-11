{{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}"
        x-data="registerForm()" x-init="init()"
        @submit.prevent="if (canSubmit) $el.submit()"
        class="space-y-6">
        @csrf

        {{-- NAME --}}
        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <div class="relative mt-1">
                <span class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                    <x-heroicon-o-user class="w-5 h-5 text-gray-400" />
                </span>
                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    x-model.trim="name"
                    placeholder="Masukkan nama lengkap"
                    required
                    autofocus
                    autocomplete="name"
                    class="block w-full h-11 pl-11 pr-4" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- EMAIL --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative mt-1">
                <span class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                    <x-heroicon-o-envelope class="w-5 h-5 text-gray-400" />
                </span>
                <x-text-input
                    id="email"
                    name="email"
                    type="email"
                    x-model.trim="email"
                    placeholder="email@domain.com"
                    required
                    autocomplete="username"
                    class="block w-full h-11 pl-11 pr-4" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- PASSWORD --}}
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative mt-1">
                <span class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                    <x-heroicon-o-lock-closed class="w-5 h-5 text-gray-400" />
                </span>

                <x-text-input
                    id="password"
                    name="password"
                    x-model="password"
                    x-bind:type="showPwd ? 'text' : 'password'"
                    placeholder="Buat password (min. 12 karakter, campur huruf, angka & simbol)"
                    required
                    autocomplete="new-password"
                    class="block w-full h-11 pl-11 pr-11" />

                <button type="button"
                    @click="showPwd = !showPwd"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                    aria-label="Tampilkan/sembunyikan password">
                    <template x-if="!showPwd"><x-heroicon-o-eye class="w-5 h-5" /></template>
                    <template x-if="showPwd"><x-heroicon-o-eye-slash class="w-5 h-5" /></template>
                </button>
            </div>

            {{-- Strength meter + checklist --}}
            <div class="mt-3">
                <div class="flex items-center justify-between text-xs text-gray-600">
                    <span>Kekuatan password</span>
                    <span x-text="labelStrength()"></span>
                </div>
                <div class="mt-1 h-2 w-full rounded-full bg-gray-100 overflow-hidden">
                    <div class="h-2 transition-all"
                        :style="`width:${score*25}%; background:${barColor()}`"></div>
                </div>

                <ul class="mt-3 space-y-1 text-sm">
                    <li class="flex items-center gap-2" :class="rules.len ? 'text-green-600' : 'text-gray-600'">
                        <template x-if="rules.len"><x-heroicon-o-check-circle class="w-4 h-4" /></template>
                        <template x-if="!rules.len"><x-heroicon-o-x-circle class="w-4 h-4 text-gray-400" /></template>
                        Minimal 12 karakter
                    </li>
                    <li class="flex items-center gap-2" :class="rules.mixed ? 'text-green-600' : 'text-gray-600'">
                        <template x-if="rules.mixed"><x-heroicon-o-check-circle class="w-4 h-4" /></template>
                        <template x-if="!rules.mixed"><x-heroicon-o-x-circle class="w-4 h-4 text-gray-400" /></template>
                        Huruf kecil & huruf besar
                    </li>
                    <li class="flex items-center gap-2" :class="rules.number ? 'text-green-600' : 'text-gray-600'">
                        <template x-if="rules.number"><x-heroicon-o-check-circle class="w-4 h-4" /></template>
                        <template x-if="!rules.number"><x-heroicon-o-x-circle class="w-4 h-4 text-gray-400" /></template>
                        Mengandung angka
                    </li>
                    <li class="flex items-center gap-2" :class="rules.symbol ? 'text-green-600' : 'text-gray-600'">
                        <template x-if="rules.symbol"><x-heroicon-o-check-circle class="w-4 h-4" /></template>
                        <template x-if="!rules.symbol"><x-heroicon-o-x-circle class="w-4 h-4 text-gray-400" /></template>
                        Mengandung simbol (mis. !@#$%^&*)
                    </li>
                    <li class="flex items-center gap-2" :class="rules.notPersonal ? 'text-green-600' : 'text-gray-600'">
                        <template x-if="rules.notPersonal"><x-heroicon-o-check-circle class="w-4 h-4" /></template>
                        <template x-if="!rules.notPersonal"><x-heroicon-o-x-circle class="w-4 h-4 text-gray-400" /></template>
                        Tidak memuat nama / username email
                    </li>
                </ul>
            </div>
        </div>

        {{-- CONFIRM PASSWORD --}}
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <div class="relative mt-1">
                <span class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                    <x-heroicon-o-lock-closed class="w-5 h-5 text-gray-400" />
                </span>

                <x-text-input
                    id="password_confirmation"
                    name="password_confirmation"
                    x-model="password2"
                    x-bind:type="showPwd2 ? 'text' : 'password'"
                    placeholder="Ulangi password yang sama"
                    required
                    autocomplete="new-password"
                    class="block w-full h-11 pl-11 pr-11" />

                <button type="button"
                    @click="showPwd2 = !showPwd2"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                    aria-label="Tampilkan/sembunyikan konfirmasi password">
                    <template x-if="!showPwd2"><x-heroicon-o-eye class="w-5 h-5" /></template>
                    <template x-if="showPwd2"><x-heroicon-o-eye-slash class="w-5 h-5" /></template>
                </button>
            </div>
            <p class="mt-1 text-xs"
                :class="password2 && password2===password ? 'text-green-600' : 'text-gray-500'">
                <span class="inline-flex items-center gap-1">
                    <x-heroicon-o-information-circle class="w-4 h-4" />
                    Harus sama dengan password.
                </span>
            </p>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- ACTIONS --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('login') }}" class="text-sm text-brand-blue hover:underline">
                {{ __('Sudah punya akun? Masuk') }}
            </a>

            {{-- PENTING: gunakan x-bind:* agar tidak di-evaluasi Blade --}}
            <x-primary-button class="inline-flex items-center"
                x-bind:disabled="!canSubmit"
                x-bind:class="!canSubmit ? 'opacity-60 cursor-not-allowed' : ''">
                <x-heroicon-o-user-plus class="w-5 h-5 mr-2" />
                {{ __('Daftar') }}
            </x-primary-button>
        </div>
    </form>

    {{-- Alpine helpers (frontend only) --}}
    <script>
        function registerForm() {
            return {
                name: '',
                email: '',
                password: '',
                password2: '',
                showPwd: false,
                showPwd2: false,
                rules: {
                    len: false,
                    mixed: false,
                    number: false,
                    symbol: false,
                    notPersonal: false
                },
                score: 0,
                get canSubmit() {
                    return this.rules.len && this.rules.mixed && this.rules.number &&
                        this.rules.symbol && this.rules.notPersonal &&
                        this.score >= 3 && this.password === this.password2;
                },
                init() {
                    this.$watch('password', () => this.evaluate());
                    this.$watch('name', () => this.evaluate());
                    this.$watch('email', () => this.evaluate());
                    this.evaluate();
                },
                evaluate() {
                    const p = this.password || '';
                    const lower = /[a-z]/.test(p);
                    const upper = /[A-Z]/.test(p);
                    const number = /\d/.test(p);
                    const symbol = /[^A-Za-z0-9]/.test(p);
                    const minLen = p.length >= 12;

                    const n = (this.name || '').toLowerCase().replace(/\s+/g, '');
                    const local = (this.email || '').toLowerCase().split('@')[0] || '';
                    const inName = !!(n && p.toLowerCase().includes(n) && n.length >= 3);
                    const inLocal = !!(local && p.toLowerCase().includes(local) && local.length >= 3);

                    this.rules.len = minLen;
                    this.rules.mixed = lower && upper;
                    this.rules.number = number;
                    this.rules.symbol = symbol;
                    this.rules.notPersonal = !(inName || inLocal);

                    this.score = [minLen, (lower && upper), number, symbol].filter(Boolean).length;
                },
                labelStrength() {
                    const labels = ['Sangat lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat kuat'];
                    return labels[this.score] || labels[0];
                },
                barColor() {
                    return ['#fca5a5', '#fca5a5', '#f59e0b', '#10b981', '#059669'][this.score] || '#fca5a5';
                }
            }
        }
    </script>
</x-guest-layout>