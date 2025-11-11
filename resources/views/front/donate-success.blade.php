{{-- resources/views/front/donate-success.blade.php --}}
<x-app-layout>
    <section class="bg-white">
        {{-- Brand bars: Merah → Biru (tanpa gradient) --}}
        <div class="h-[2px] w-full" style="background-color:#D21F26"></div>
        <div class="h-[2px] w-full" style="background-color:#145EFC"></div>

        @php
        // Fallback jika controller belum mengirim variabel
        $donationId = $donationId ?? 'TRX-' . strtoupper(Str::random(8));
        $amount = isset($amount) ? (int) $amount : 150000;
        $program = $program ?? 'Program Pendidikan Anak';
        $method = $method ?? 'QRIS';
        $donorName = $donorName ?? auth()->user()->name ?? 'Donatur Baik';
        $email = $email ?? auth()->user()->email ?? 'you@example.com';
        @endphp

        <div class="mx-auto max-w-3xl px-6 py-12">
            {{-- Heading card --}}
            <div class="rounded-3xl border border-gray-200 bg-white p-6 md:p-8 shadow-sm">
                <div class="flex flex-col items-center text-center">
                    <div class="h-16 w-16 rounded-full bg-green-50 text-green-600 grid place-items-center ring-1 ring-green-200">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M9 12l2 2 4-4 2 2-6 6-4-4z" />
                        </svg>
                    </div>

                    <h1 class="mt-5 text-3xl md:text-4xl font-bold text-gray-900">Terima kasih, {{ $donorName }}!</h1>
                    <p class="mt-2 text-gray-600">
                        Donasi Anda telah <span class="font-medium text-gray-900">berhasil tercatat</span>.
                        Bukti dan ringkasan transaksi sudah kami kirim ke <span class="font-medium">{{ $email }}</span>.
                    </p>

                    {{-- Info stripe merah (brand) --}}
                    <div class="mt-6 h-1.5 w-24 rounded-full" style="background-color:#D21F26"></div>
                </div>

                {{-- Ringkasan donasi --}}
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-gray-200 p-4">
                        <div class="text-xs uppercase tracking-wide text-gray-500">Program</div>
                        <div class="mt-1 text-base font-semibold text-gray-900">{{ $program }}</div>
                    </div>
                    <div class="rounded-2xl border border-gray-200 p-4">
                        <div class="text-xs uppercase tracking-wide text-gray-500">Nominal</div>
                        <div class="mt-1 text-base font-semibold text-gray-900">Rp {{ number_format($amount,0,',','.') }}</div>
                    </div>
                    <div class="rounded-2xl border border-gray-200 p-4">
                        <div class="text-xs uppercase tracking-wide text-gray-500">Metode</div>
                        <div class="mt-1 inline-flex items-center gap-2">
                            <span class="inline-flex h-2.5 w-2.5 rounded-full" style="background-color:#145EFC"></span>
                            <span class="text-base font-semibold text-gray-900">{{ $method }}</span>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-200 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs uppercase tracking-wide text-gray-500">ID Transaksi</div>
                                <div id="trxId" class="mt-1 text-base font-semibold text-gray-900">{{ $donationId }}</div>
                            </div>
                            <button id="copyBtn"
                                class="ml-3 inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm ring-1 ring-gray-200 hover:bg-gray-50"
                                aria-label="Salin ID transaksi">
                                <svg class="h-4 w-4 text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M16 1H4a2 2 0 00-2 2v12h2V3h12V1zm3 4H8a2 2 0 00-2 2v14a2 2 0 002 2h11a2 2 0 002-2V7a2 2 0 00-2-2zm0 16H8V7h11v14z" />
                                </svg>
                                Salin
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Aksi utama --}}
                <div class="mt-8 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="text-xs sm:text-sm text-gray-500">
                        Estimasi email masuk: <span class="font-medium">1–3 menit</span>. Tidak menerima email?
                        <a href="{{ route('contact') }}" class="text-gray-900 underline decoration-from-font">Hubungi kami</a>.
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="window.print()"
                            class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 font-semibold ring-1 ring-gray-200 hover:bg-gray-50"
                            style="color:#145EFC">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M6 9V2h12v7H6zm0 13v-6h12v6H6zM4 10h16a2 2 0 012 2v4h-4v-4H6v4H2v-4a2 2 0 012-2z" />
                            </svg>
                            Cetak Bukti
                        </button>
                        @php
                        $shareUrl = urlencode(url()->current());
                        $shareText = urlencode("Saya baru berdonasi ke \"$program\". Yuk ikut berdampak!");
                        $waLink = "https://wa.me/?text={$shareText}%20{$shareUrl}";
                        $mailLink = "mailto:?subject=Donasi%20untuk%20{$program}&body={$shareText}%20{$shareUrl}";
                        @endphp
                        <a href="{{ $waLink }}" target="_blank" rel="noopener"
                            class="inline-flex items-center gap-2 rounded-xl px-4 py-2 font-semibold text-white hover:opacity-95"
                            style="background-color:#25D366">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.52 3.48A11.94 11.94 0 0012.04 0C5.44 0 .09 5.35.09 11.95c0 2.1.55 4.12 1.6 5.92L0 24l6.29-1.64a11.9 11.9 0 005.75 1.47h.01c6.6 0 11.95-5.35 11.95-11.95 0-3.2-1.25-6.21-3.48-8.4zm-8.48 19.1h-.01A9.93 9.93 0 016.2 21.1l-.45-.27-3.73.97.99-3.64-.29-.47a9.94 9.94 0 1118.32-5.74c0 5.49-4.46 9.95-9.95 9.95zm5.48-7.47c-.3-.15-1.78-.88-2.06-.98-.28-.1-.48-.15-.68.15-.2.3-.78.98-.96 1.18-.18.2-.36.22-.66.07-.3-.15-1.25-.46-2.38-1.46-.88-.79-1.48-1.77-1.66-2.07-.18-.3-.02-.46.13-.61.13-.13.3-.33.45-.5.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.68-1.63-.93-2.23-.24-.58-.49-.5-.68-.5-.17 0-.37-.02-.57-.02-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.5s1.07 2.9 1.22 3.1c.15.2 2.1 3.21 5.08 4.5.71.31 1.27.49 1.71.62.72.23 1.37.2 1.88.12.57-.08 1.78-.73 2.03-1.43.25-.7.25-1.3.17-1.43-.08-.13-.28-.2-.58-.35z" />
                            </svg>
                            Bagikan (WA)
                        </a>
                        <a href="{{ $mailLink }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 font-semibold ring-1 ring-gray-200 hover:bg-gray-50"
                            style="color:#145EFC">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 5a2 2 0 012-2h14a2 2 0 012 2v1l-9 6L3 6V5z" />
                                <path d="M3 8l9 6 9-6v9a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                            </svg>
                            Bagikan via Email
                        </a>
                    </div>
                </div>
            </div>

            {{-- Kartu langkah selanjutnya --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('home') }}"
                    class="group rounded-2xl border border-gray-200 bg-white p-5 hover:shadow-sm transition">
                    <div class="flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded-xl text-white" style="background-color:#145EFC">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 3l9 8-1.5 1.33L18 11.1V20H6v-8.9l-1.5 1.23L3 11l9-8z" />
                            </svg>
                        </span>
                        <div>
                            <div class="font-semibold text-gray-900">Kembali ke Beranda</div>
                            <div class="text-sm text-gray-600">Lihat program terbaru & laporan.</div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('home') }}#campaigns"
                    class="group rounded-2xl border border-gray-200 bg-white p-5 hover:shadow-sm transition">
                    <div class="flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded-xl text-white" style="background-color:#D21F26">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4 4h6v6H4V4zm0 10h6v6H4v-6zm10-10h6v6h-6V4zm0 10h6v6h-6v-6z" />
                            </svg>
                        </span>
                        <div>
                            <div class="font-semibold text-gray-900">Jelajahi Kampanye</div>
                            <div class="text-sm text-gray-600">Temukan program yang ingin Anda dukung.</div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('contact') }}"
                    class="group rounded-2xl border border-gray-200 bg-white p-5 hover:shadow-sm transition">
                    <div class="flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded-xl text-white" style="background-color:#145EFC">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M2 4h20v14H6l-4 4V4z" />
                            </svg>
                        </span>
                        <div>
                            <div class="font-semibold text-gray-900">Butuh Bantuan?</div>
                            <div class="text-sm text-gray-600">Tim kami siap membantu Anda.</div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Info tambahan (opsional) --}}
            <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="text-sm text-gray-700">
                        Mohon simpan <span class="font-semibold">ID Transaksi</span> Anda untuk keperluan verifikasi cepat.
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ url()->current() }}?download=pdf"
                            class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold ring-1 ring-gray-200 hover:bg-gray-50"
                            style="color:#145EFC">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zM8 13h8v2H8v-2zm0 4h8v2H8v-2zm6-9V3.5L19.5 9H14z" />
                            </svg>
                            Unduh (PDF)
                        </a>
                        <a href="{{ url()->current() }}?resend=1"
                            class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold text-white hover:opacity-95"
                            style="background-color:#D21F26">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M2 6a2 2 0 012-2h16a2 2 0 012 2v.4l-10 6L2 6.4V6zm0 3.3l9.4 5.6a1.5 1.5 0 001.2 0L22 9.3V18a2 2 0 01-2 2H4a2 2 0 01-2-2V9.3z" />
                            </svg>
                            Kirim Ulang Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-footer />

    {{-- Mini interaksi --}}
    <script>
        (function() {
            const btn = document.getElementById('copyBtn');
            const trx = document.getElementById('trxId');
            if (btn && trx) {
                btn.addEventListener('click', async () => {
                    try {
                        await navigator.clipboard.writeText(trx.textContent.trim());
                        btn.innerHTML = `<svg class="h-4 w-4 text-green-600" viewBox="0 0 24 24" fill="currentColor"><path d="M9 12l2 2 4-4 2 2-6 6-4-4z"/></svg> Tersalin`;
                        setTimeout(() => {
                            btn.innerHTML = `<svg class="h-4 w-4 text-gray-600" viewBox="0 0 24 24" fill="currentColor"><path d="M16 1H4a2 2 0 00-2 2v12h2V3h12V1zm3 4H8a2 2 0 00-2 2v14a2 2 0 002 2h11a2 2 0 002-2V7a2 2 0 00-2-2zm0 16H8V7h11v14z"/></svg> Salin`;
                        }, 1800);
                    } catch (e) {
                        alert('Gagal menyalin ID transaksi.');
                    }
                });
            }
        })();
    </script>
</x-app-layout>