@php
  $items = [
    ['name'=>'Overview','route'=>route('superadmin.dashboard'),'active'=>request()->routeIs('superadmin.dashboard')],
    ['name'=>'Campaigns','route'=>route('superadmin.campaigns.index'),'active'=>request()->routeIs('superadmin.campaigns.*')],
    ['name'=>'Donations','route'=>route('superadmin.donations.index'),'active'=>request()->routeIs('superadmin.donations.*')],
    ['name'=>'Users','route'=>route('superadmin.users.index'),'active'=>request()->routeIs('superadmin.users.*')],
    ['name'=>'Settings','route'=>route('superadmin.settings'),'active'=>request()->routeIs('superadmin.settings')],
  ];
@endphp
<nav class="w-full rounded-3xl bg-white/80 backdrop-blur-xl ring-1 ring-gray-200 shadow-sm p-4 md:p-5 space-y-1">
  <div class="px-2 pb-3">
    <div class="text-xs uppercase tracking-wider text-gray-500">SUPERADMIN</div>
    <div class="text-sm text-gray-700">Panel Kontrol</div>
  </div>

  @foreach ($items as $it)
    @php
      $isActive = !empty($it['active']);
      $hasIcon  = !empty($it['icon']);
    @endphp

    <a href="{{ $it['route'] ?? '#' }}"
       class="group flex items-center gap-3 px-3 py-2 rounded-xl transition
              {{ $isActive ? 'bg-blue-600 text-white shadow' : 'hover:bg-gray-50 text-gray-700' }}">
      @if($hasIcon)
        <svg class="w-5 h-5 {{ $isActive ? 'text-white' : 'text-blue-700/70 group-hover:text-blue-700' }}"
             viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="{{ $it['icon'] }}"></path>
        </svg>
      @else
        {{-- Placeholder kecil agar alignment konsisten saat tanpa ikon --}}
        <span class="inline-block w-5 h-5"></span>
      @endif

      <span class="text-sm font-medium">{{ $it['name'] ?? '-' }}</span>

      @if($isActive)
        <span class="ml-auto h-2 w-2 rounded-full bg-white/90"></span>
      @endif
    </a>
  @endforeach

  <div class="pt-3 mt-2 border-t border-gray-200">
    <a href="{{ route('home') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-50">
      <span class="inline-block w-5 h-5"></span>
      <span class="text-sm">Back to Site</span>
    </a>
  </div>
</nav>
