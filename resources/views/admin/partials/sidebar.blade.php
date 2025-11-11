@php
$items = [
  ['label'=>'Dashboard','route'=>route('admin.dashboard'),'active'=>request()->routeIs('admin.dashboard')],
  ['label'=>'Campaigns','route'=>route('admin.campaigns.index'),'active'=>request()->routeIs('admin.campaigns.*')],
  ['label'=>'Users','route'=>route('admin.users.index'),'active'=>request()->routeIs('admin.users.*')], // NEW
  ['label'=>'Reports','route'=>route('admin.reports.donations'),'active'=>request()->routeIs('admin.reports.*')],
];
@endphp

<nav class="space-y-1">
  @foreach($items as $it)
    <a href="{{ $it['route'] }}"
       class="block px-3 py-2 rounded-xl {{ $it['active'] ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
      {{ $it['label'] }}
    </a>
  @endforeach
</nav>
