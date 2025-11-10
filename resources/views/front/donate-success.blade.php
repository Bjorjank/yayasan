<x-app-layout>
    <section class="bg-white">
        <div class="max-w-lg mx-auto px-6 py-16 text-center">
            <div class="mx-auto h-16 w-16 rounded-full bg-green-50 text-green-600 grid place-items-center ring-1 ring-green-200">
                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 12l2 2 4-4 2 2-6 6-4-4z" />
                </svg>
            </div>
            <h1 class="mt-6 text-3xl font-bold text-gray-900">Terima kasih!</h1>
            <p class="mt-2 text-gray-600">Donasi Anda telah tercatat. Email konfirmasi akan kami kirimkan.</p>
            <div class="mt-6">
                <a href="{{ route('home') }}" class="rounded-xl bg-blue-600 text-white px-5 py-2.5 hover:bg-blue-700">Kembali ke Beranda</a>
            </div>
        </div>
    </section>
</x-app-layout>