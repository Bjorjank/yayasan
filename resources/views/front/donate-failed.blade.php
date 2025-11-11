{{-- resources/views/front/donate-failed.blade.php --}}
<x-app-layout>
    <section class="bg-white">
        {{-- Brand bars: Merah → Biru (tanpa gradient) --}}
        <div class="h-[2px] w-full" style="background-color:#D21F26"></div>
        <div class="h-[2px] w-full" style="background-color:#145EFC"></div>

        @php
        // Fallback bila controller belum mengirim variabel
        $donationId = $donationId ?? 'TRX-' . strtoupper(Str::random(8));
        $amount = isset($amount) ? (int) $amount : 150000;
        $program = $program ?? 'Program Pendidikan Anak';
        $method = $method ?? 'QRIS';
        $errorCode = $errorCode ?? 'PAY-ERR-408'; // contoh: timeout / canceled / invalid-signature
        $errorText = $errorText ?? 'Pembayaran tidak terselesaikan (timeout / dibatalkan).';
        $supportEmail= $supportEmail?? 'halo@yayasan.test';
        @endphp

        <div class="mx-auto max-w-3xl px-6 py-12">
            {{-- Header Card --}}
            <div class="rounded-3xl border border-gray-200 bg-white p-6 md:p-8 shadow-sm">
                <div class="flex flex-col items-center text-center">
                    <div class="h-16 w-16 rounded-full bg-red-50 text-red-600 grid place-items-center ring-1 ring-red-200">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2L2 22h20L12 2zm0 14a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm-1-10h2v8h-2V6z" />
                        </svg>
                    </div>

                    <h1 class="mt-5 text-3xl md:text-4xl font-bold text-gray-900">Transaksi Gagal</h1>
                    <p class="mt-2 text-gray-600">
                        Maaf, transaksi Anda belum berhasil diproses. Anda bisa mencoba lagi atau menggunakan metode pembayaran lain.
                    </p>

                    {{-- Info stripe merah (brand) --}}
                    <div class="mt-6 h-1.5 w-24 rounded-full" style="background-color:#D21F26"></div>
                </div>

                {{-- Ringkasan & Error --}}
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-gray-200 p-4">
                        <div class="text-xs uppercase tracking-wide text-gray-500">Program</div>
                        <div class="mt-1 text-base font-semibold text-gray-900">{{ $program }}</div>
                    </div>
                    <div class="rounded-2xl border border-gray-200 p-4">
                        <div class="text-xs uppercase tracking-wide text-gray-500">Nominal</div>
                        <div class="mt-1 text-base font-semibold text-gray-900">
                            Rp {{ number_format($amount,0,',','.') }}
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-200 p-4">
                        <div class="text-xs uppercase tracking-wide text-gray-500">Metode</div>
                        <div class="mt-1 inline-flex items-center gap-2">
                            <span class="inline-flex h-2.5 w-2.5 rounded-full" style="background-color:#145EFC"></span>
                            <span class="text-base font-semibold text-gray-900">{{ $method }}</span>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-200 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-xs uppercase tracking-wide text-gray-500">ID Transaksi</div>
                                <div id="trxId" class="mt-1 text-base font-semibold text-gray-900">{{ $donationId }}</div>
                                <div class="mt-2 text-xs text-red-700 inline-flex items-center gap-2 bg-red-50 px-2.5 py-1 rounded-md ring-1 ring-red-100">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M11 7h2v6h-2V7zm0 8h2v2h-2v-2z" />
                                    </svg>
                                    <span id="errText">{{ $errorText }}</span>
                                </div>
                                <div class="mt-1 text-[11px] text-gray-500">
                                    Kode error: <span id="errCode" class="font-medium text-gray-700">{{ $errorCode }}</span>
                                </div>
                            </div>
                            <button id="copyErrBtn"
                                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm ring-1 ring-gray-200 hover:bg-gray-50"
                                aria-label="Salin info error">
                                <svg class="h-4 w-4 text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M16 1H4a2 2 0 00-2 2v12h2V3h12V1zm3 4H8a2 2 0 00-2 2v14a2 2 0 002 2h11a2 2 0 002-2V7a2 2 0 00-2-2zm0 16H8V7h11v14z" />
                                </svg>
                                Salin Error
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Aksi utama --}}
                <div class="mt-8 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="text-xs sm:text-sm text-gray-500">
                        Jika tetap gagal, kirimkan <span class="font-medium">ID Transaksi</span> dan <span class="font-medium">Kode Error</span> ke
                        <a href="mailto:{{ $supportEmail }}" class="text-gray-900 underline decoration-from-font">{{ $supportEmail }}</a>.
                    </div>
                    <div class="flex flex-wrap gap-2">
                        {{-- Ganti route sesuai flow pembayaran Anda --}}
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center gap-2 rounded-xl px-4 py-2 font-semibold text-white hover:opacity-95"
                            style="background-color:#D21F26">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L2 7v6c0 5.55 3.84 10.74 10 12 6.16-1.26 10-6.45 10-12V7l-10-5zm0 17.5l-5.5-5.5L8 12l4 4 8-8 1.5 1.5L12 19.5z" />
                            </svg>
                            Coba Lagi
                        </a>
                        <a href="{{ route('home') }}#payment-methods"
                            class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 font-semibold ring-1 ring-gray-200 hover:bg-gray-50"
                            style="color:#145EFC">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M21 7H3V5h18v2zm0 2H3v10h18V9zM5 15h6v2H5v-2z" />
                            </svg>
                            Ganti Metode
                        </a>
                        <a href="{{ route('contact') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 font-semibold ring-1 ring-gray-200 hover:bg-gray-50"
                            style="color:#145EFC">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M2 4h20v14H6l-4 4V4z" />
                            </svg>
                            Hubungi Dukungan
                        </a>
                    </div>
                </div>
            </div>

            {{-- Troubleshooting --}}
            <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-5">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-2.5 w-2.5 rounded-full" style="background-color:#145EFC"></span>
                    <h2 class="font-semibold text-gray-900">Penyebab Umum & Solusi Cepat</h2>
                </div>
                <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <details class="group rounded-xl ring-1 ring-gray-200 bg-white p-4">
                        <summary class="flex items-center justify-between cursor-pointer font-medium text-gray-900">
                            Limit harian bank / e-wallet
                            <svg class="h-5 w-5 text-gray-500 transition-transform group-open:rotate-180" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 14l-6-6h12l-6 6z" />
                            </svg>
                        </summary>
                        <p class="mt-2 text-sm text-gray-700">Coba nominal lebih kecil atau metode lain (VA/QRIS). Pastikan saldo cukup.</p>
                    </details>
                    <details class="group rounded-xl ring-1 ring-gray-200 bg-white p-4">
                        <summary class="flex items-center justify-between cursor-pointer font-medium text-gray-900">
                            Timeout halaman pembayaran
                            <svg class="h-5 w-5 text-gray-500 transition-transform group-open:rotate-180" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 14l-6-6h12l-6 6z" />
                            </svg>
                        </summary>
                        <p class="mt-2 text-sm text-gray-700">Tutup tab lama lalu mulai ulang. Koneksi stabil sangat disarankan.</p>
                    </details>
                    <details class="group rounded-xl ring-1 ring-gray-200 bg-white p-4">
                        <summary class="flex items-center justify-between cursor-pointer font-medium text-gray-900">
                            Kode bayar kadaluarsa
                            <svg class="h-5 w-5 text-gray-500 transition-transform group-open:rotate-180" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 14l-6-6h12l-6 6z" />
                            </svg>
                        </summary>
                        <p class="mt-2 text-sm text-gray-700">Regenerasi kode pembayaran terbaru sebelum melakukan transfer.</p>
                    </details>
                </div>
            </div>

            {{-- Arahkan pengguna --}}
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
                            <div class="text-sm text-gray-600">Lihat program & pengumuman terbaru.</div>
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
                            <div class="font-semibold text-gray-900">Pilih Kampanye Lain</div>
                            <div class="text-sm text-gray-600">Dukungan kecilmu tetap berarti besar.</div>
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
        </div>
    </section>

    <x-footer />

    {{-- Interaksi kecil --}}
    <script>
        (function() {
            const copyBtn = document.getElementById('copyErrBtn');
            const trxId = document.getElementById('trxId')?.textContent?.trim() || '';
            const errCode = document.getElementById('errCode')?.textContent?.trim() || '';
            const errText = document.getElementById('errText')?.textContent?.trim() || '';

            copyBtn?.addEventListener('click', async () => {
                const payload = `ID: ${trxId}\nKode: ${errCode}\nKet: ${errText}`;
                try {
                    await navigator.clipboard.writeText(payload);
                    copyBtn.innerHTML = `<svg class="h-4 w-4 text-green-600" viewBox="0 0 24 24" fill="currentColor"><path d="M9 12l2 2 4-4 2 2-6 6-4-4z"/></svg> Tersalin`;
                    setTimeout(() => {
                        copyBtn.innerHTML = `<svg class="h-4 w-4 text-gray-600" viewBox="0 0 24 24" fill="currentColor"><path d="M16 1H4a2 2 0 00-2 2v12h2V3h12V1zm3 4H8a2 2 0 00-2 2v14a2 2 0 002 2h11a2 2 0 002-2V7a2 2 0 00-2-2zm0 16H8V7h11v14z"/></svg> Salin Error`;
                    }, 1800);
                } catch {
                    alert('Gagal menyalin info error.');
                }
            });
        })();
    </script>
>>>>>>> fix-frontend
</x-app-layout>