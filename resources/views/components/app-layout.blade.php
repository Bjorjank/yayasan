<!-- {{-- resources/views/components/app-layout.blade.php --}}
@props(['header' => null])

<div class="min-h-screen bg-gray-50 flex flex-col">
    @if ($header)
    <header>
        {{ $header }}
    </header>
    @endif

    <main class="flex-1">
        {{ $slot }}
    </main>

    <x-footer /> {{-- ← Footer hanya di sini --}}
</div> -->