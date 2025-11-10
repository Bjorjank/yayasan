<x-app-layout>
    <section class="bg-white">
        <div class="max-w-lg mx-auto px-6 py-16 text-center">
            <div class="mx-auto h-16 w-16 rounded-full bg-red-50 text-red-600 grid place-items-center ring-1 ring-red-200">
                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L2 22h20L12 2zm0 14a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm-1-8h2v6h-2V8z" />
                </svg>
            </div>
            <h1 class="mt-6 text-3xl font-bold text-gray-900">Transaksi Gagal</h1>
            <p class="mt-2 text-gray-600">Maaf, transaksi tidak berhasil. Silakan coba lagi.</p>
            <div class="mt-6">
                <a href="{{ route('home') }}" class="rounded-xl bg-blue-600 text-white px-5 py-2.5 hover:bg-blue-700">Kembali ke Beranda</a>
            </div>
        </div>
    </section>
</x-app-layout>