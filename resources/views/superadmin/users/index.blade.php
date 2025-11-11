@extends('layouts.superadmin')

@section('title','Superadmin — Users')

@section('content')
<style>[x-cloak]{display:none!important}</style>

<div x-data="usersPage" x-init="init()" class="space-y-6">

  {{-- Header & actions --}}
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <h1 class="text-2xl md:text-3xl font-black text-gray-900">Users</h1>
      <p class="text-gray-600">Kelola pengguna & lihat kumulasi donasi per user.</p>
    </div>

    <div class="flex items-center gap-2">
      <form method="get" class="flex items-center gap-2">
        <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama/email…"
               class="w-56 border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"/>
        <button class="px-3 py-2 rounded-xl bg-blue-600 text-white text-sm hover:bg-blue-700">Cari</button>
        @if($q !== '')
          <a href="{{ route('superadmin.users.index') }}" class="px-3 py-2 rounded-xl ring-1 ring-gray-200 text-gray-700 bg-white hover:bg-gray-50 text-sm">Reset</a>
        @endif
      </form>

      <button @click="openCreate=true"
              class="px-3 py-2 rounded-xl bg-emerald-600 text-white text-sm hover:bg-emerald-700">
        + Tambah User
      </button>
    </div>
  </div>

{{-- Table --}}
<div class="rounded-3xl overflow-hidden ring-1 ring-gray-200 bg-white shadow-sm">
  <div class="px-5 py-4 border-b border-gray-100 text-sm text-gray-600 flex items-center justify-between">
    <div>
      Menampilkan <span class="font-semibold">{{ $users->count() }}</span> dari
      <span class="font-semibold">{{ $users->total() }}</span> pengguna
    </div>
    @if($q ?? false)
      <div class="hidden sm:block text-xs text-gray-500">Kata kunci: “{{ $q }}”</div>
    @endif
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 text-gray-600 sticky top-0 z-10">
        <tr>
          <th class="p-3 text-left">User</th>
          <th class="p-3 text-left">Email</th>
          <th class="p-3 text-left">Role</th>
          <th class="p-3 text-right">Total Donasi</th>
          <th class="p-3 text-left">Transaksi</th>
          <th class="p-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @forelse($users as $u)
          @php
            $roleName = $u->getRoleNames()->first() ?? '—';
            $initials = collect(explode(' ', trim($u->name)))->filter()->map(fn($p)=>mb_substr($p,0,1))->take(2)->implode('');
            $roleClasses = match(strtolower($roleName)) {
              'superadmin' => 'bg-purple-50 text-purple-700 ring-purple-200',
              'admin'      => 'bg-blue-50 text-blue-700 ring-blue-200',
              'loket'      => 'bg-amber-50 text-amber-700 ring-amber-200',
              default      => 'bg-gray-50 text-gray-700 ring-gray-200',
            };
          @endphp

          <tr class="hover:bg-gray-50/60">
            {{-- User --}}
            <td class="p-3">
              <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-gray-100 ring-1 ring-gray-200 flex items-center justify-center font-semibold text-gray-700">
                  {{ $initials ?: 'U' }}
                </div>
                <div>
                  <div class="font-semibold text-gray-900 leading-tight">{{ $u->name }}</div>
                  <div class="text-[11px] text-gray-500">ID #{{ $u->id }}</div>
                </div>
              </div>
            </td>

            {{-- Email --}}
            <td class="p-3 text-gray-700 break-all">{{ $u->email }}</td>

            {{-- Role --}}
            <td class="p-3">
              <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs ring-1 {{ $roleClasses }}">
                {{ ucfirst($roleName) }}
              </span>
            </td>

            {{-- Total Donasi --}}
            <td class="p-3 text-right font-semibold text-gray-900 whitespace-nowrap">
              Rp {{ number_format((int)($u->donated_sum ?? 0), 0, ',', '.') }}
            </td>

            {{-- Jumlah Transaksi --}}
            <td class="p-3 text-gray-700">
              {{ number_format((int)($u->donated_cnt ?? 0)) }}
            </td>

            {{-- Aksi --}}
            <td class="p-3">
              <div class="flex items-center justify-end gap-1.5">
                <a href="{{ route('superadmin.users.show', $u) }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg ring-1 ring-gray-200 hover:bg-gray-50"
                   title="Detail user">
                  {{-- icon: eye --}}
                  <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="2"/>
                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                  </svg>
                  <span class="hidden lg:inline">Detail</span>
                </a>

                <button
                  @click="openEdit({ id: {{ $u->id }},
                                      name: @js($u->name),
                                      email: @js($u->email),
                                      role: @js($roleName) })"
                  class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg ring-1 ring-gray-200 hover:bg-gray-50"
                  title="Edit user">
                  {{-- icon: pencil --}}
                  <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                    <path d="M4 21l4.5-1 10-10a2.1 2.1 0 10-3-3l-10 10L4 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span class="hidden lg:inline">Edit</span>
                </button>

                <button
                  @click="openDelete({ id: {{ $u->id }}, name: @js($u->name) })"
                  class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700"
                  title="Hapus user">
                  {{-- icon: trash --}}
                  <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                    <path d="M4 7h16M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2m-1 0l-1 13a2 2 0 01-2 2h-2a2 2 0 01-2-2L7 7h10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span class="hidden lg:inline">Hapus</span>
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td class="p-8 text-center text-gray-500" colspan="6">
              Belum ada user.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="px-5 py-4 border-t border-gray-100">
    {{ $users->onEachSide(1)->links() }}
  </div>
</div>

  {{-- ===== Modals ===== --}}

{{-- ===== Create User Modal (replaces old block) ===== --}}
<div x-cloak x-show="openCreate" x-transition
     class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-4">
  <div @click.away="openCreate=false"
       class="w-full max-w-xl rounded-3xl bg-white/90 backdrop-blur ring-1 ring-gray-200 shadow-2xl overflow-hidden">

    {{-- Header --}}
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
      <div>
        <h3 class="text-lg font-semibold text-gray-900">Tambah User</h3>
        <p class="text-xs text-gray-500">Lengkapi data pengguna dan tentukan perannya.</p>
      </div>
      <button @click="openCreate=false"
              class="inline-flex h-9 w-9 items-center justify-center rounded-xl hover:bg-gray-100 text-gray-500"
              aria-label="Tutup modal">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
          <path d="M6 6l12 12M6 18L18 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>
    </div>

    {{-- Form --}}
    <form method="post" action="{{ route('superadmin.users.store') }}"
          x-data="{
            showPwd:false,
            showPwd2:false,
            pwd:'', pwd2:'',
            strength:0,
            level(){ // 0..4
              let s=0;
              if(this.pwd.length>=8) s++;
              if(/[A-Z]/.test(this.pwd)) s++;
              if(/[a-z]/.test(this.pwd)) s++;
              if(/[0-9\W]/.test(this.pwd)) s++;
              this.strength=s; return s;
            },
            barWidth(){ return `${(this.level()/4)*100}%`; },
            barColor(){
              return this.strength<=1 ? 'bg-red-500'
                   : this.strength==2 ? 'bg-amber-500'
                   : this.strength==3 ? 'bg-lime-500'
                   : 'bg-emerald-600';
            }
          }"
          class="px-6 py-5 space-y-4">

      @csrf

      {{-- Nama --}}
      <div>
        <label class="text-sm font-medium text-gray-800">Nama</label>
        <input name="name" required
               class="mt-1 w-full rounded-xl border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
               placeholder="Nama lengkap">
        @error('name')
          <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
        @enderror
      </div>

      {{-- Email --}}
      <div>
        <label class="text-sm font-medium text-gray-800">Email</label>
        <input type="email" name="email" required
               class="mt-1 w-full rounded-xl border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
               placeholder="email@contoh.com">
        @error('email')
          <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
        @enderror
      </div>

      {{-- Password + toggle eye + strength --}}
      <div>
        <label class="text-sm font-medium text-gray-800">Password</label>
        <div class="mt-1 relative">
          <input :type="showPwd ? 'text' : 'password'"
                 x-model="pwd"
                 name="password" required
                 class="w-full rounded-xl border pl-3 pr-10 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                 placeholder="Minimal 8 karakter"
                 @input="level()">
          <button type="button" @click="showPwd=!showPwd"
                  class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700"
                  aria-label="Toggle password">
            <template x-if="!showPwd">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="2"/>
                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
              </svg>
            </template>
            <template x-if="showPwd">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                <path d="M3 3l18 18M10.6 10.6A3 3 0 0012 15a3 3 0 002.4-4.4M9.88 4.24A10.3 10.3 0 0112 4c6.5 0 10 8 10 8a18.2 18.2 0 01-4.06 4.86M6.19 6.19A18.3 18.3 0 002 12s3.5 7 10 7c1.43 0 2.79-.3 4.03-.82"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </template>
          </button>
        </div>
        {{-- Strength meter --}}
        <div class="mt-2">
          <div class="h-2 w-full rounded-full bg-gray-100 overflow-hidden">
            <div class="h-2 rounded-full transition-all"
                 :class="barColor()"
                 :style="`width:${barWidth()}`"></div>
          </div>
          <div class="mt-1 flex items-center justify-between text-[11px] text-gray-500">
            <span>Kekuatan password</span>
            <span x-text="['Sangat lemah','Lemah','Cukup','Kuat','Sangat kuat'][strength] || '—'"></span>
          </div>
        </div>
        @error('password')
          <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
        @enderror
      </div>

      {{-- Konfirmasi Password + toggle eye --}}
      <div>
        <label class="text-sm font-medium text-gray-800">Konfirmasi Password</label>
        <div class="mt-1 relative">
          <input :type="showPwd2 ? 'text' : 'password'"
                 x-model="pwd2"
                 name="password_confirmation" required
                 class="w-full rounded-xl border pl-3 pr-10 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                 placeholder="Ulangi password">
          <button type="button" @click="showPwd2=!showPwd2"
                  class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700"
                  aria-label="Toggle password confirmation">
            <template x-if="!showPwd2">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="2"/>
                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
              </svg>
            </template>
            <template x-if="showPwd2">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                <path d="M3 3l18 18M10.6 10.6A3 3 0 0012 15a3 3 0 002.4-4.4M9.88 4.24A10.3 10.3 0 0112 4c6.5 0 10 8 10 8a18.2 18.2 0 01-4.06 4.86M6.19 6.19A18.3 18.3 0 002 12s3.5 7 10 7c1.43 0 2.79-.3 4.03-.82"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </template>
          </button>
        </div>
      </div>

      {{-- Role --}}
      <div>
        <label class="text-sm font-medium text-gray-800">Role</label>
        <select name="role"
                class="mt-1 w-full rounded-xl border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
          @foreach($allowedRoles as $r)
            <option value="{{ $r }}">{{ $r }}</option>
          @endforeach
        </select>
        @error('role')
          <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
        @enderror
      </div>

      {{-- Footer Actions --}}
      <div class="pt-2 flex items-center justify-end gap-2">
        <button type="button" @click="openCreate=false"
                class="px-4 py-2 rounded-xl ring-1 ring-gray-200 bg-white hover:bg-gray-50">Batal</button>
        <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
          Simpan User
        </button>
      </div>
    </form>
  </div>
</div>

  {{-- Edit Modal --}}
  <div x-cloak x-show="openEditModal" x-transition
       class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-4">
    <div @click.away="openEditModal=false" class="w-full max-w-lg rounded-2xl bg-white ring-1 ring-gray-200 shadow-xl">
      <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-900">Edit User</h3>
        <button @click="openEditModal=false" class="text-gray-400 hover:text-gray-600">✕</button>
      </div>
      <form :action="editAction" method="post" class="p-5 space-y-4">
        @csrf
        @method('PUT')
        <div>
          <label class="text-sm font-medium">Nama</label>
          <input name="name" x-model="edit.name" required class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"/>
        </div>
        <div>
          <label class="text-sm font-medium">Email</label>
          <input type="email" name="email" x-model="edit.email" required class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"/>
        </div>
        <div class="grid sm:grid-cols-2 gap-3">
          <div>
            <label class="text-sm font-medium">Password (opsional)</label>
            <input type="password" name="password" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"/>
          </div>
          <div>
            <label class="text-sm font-medium">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"/>
          </div>
        </div>
        <div>
          <label class="text-sm font-medium">Role</label>
          <select name="role" x-model="edit.role" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
            @foreach($allowedRoles as $r)
              <option value="{{ $r }}">{{ $r }}</option>
            @endforeach
          </select>
        </div>

        <div class="pt-2 flex items-center justify-end gap-2">
          <button type="button" @click="openEditModal=false" class="px-4 py-2 rounded-xl ring-1 ring-gray-200">Batal</button>
          <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Delete Modal --}}
  <div x-cloak x-show="openDeleteModal" x-transition
       class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-4">
    <div @click.away="openDeleteModal=false" class="w-full max-w-md rounded-2xl bg-white ring-1 ring-gray-200 shadow-xl">
      <div class="p-5">
        <h3 class="font-semibold text-gray-900">Hapus User</h3>
        <p class="text-sm text-gray-600 mt-2">
          Yakin ingin menghapus user <span class="font-semibold" x-text="del.name"></span>? Tindakan ini tidak dapat dibatalkan.
        </p>
        <form :action="deleteAction" method="post" class="mt-4 flex items-center justify-end gap-2">
          @csrf
          @method('DELETE')
          <button type="button" @click="openDeleteModal=false" class="px-4 py-2 rounded-xl ring-1 ring-gray-200">Batal</button>
          <button class="px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700">Hapus</button>
        </form>
      </div>
    </div>
  </div>

</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('usersPage', () => ({
    openCreate: false,
    openEditModal: false,
    openDeleteModal: false,

    edit: { id:null, name:'', email:'', role:'' },
    del: { id:null, name:'' },

    editAction: '#',
    deleteAction: '#',

    init(){},

    openEdit(payload){
      this.edit = { ...payload };
      this.editAction = `{{ url('/superadmin/users') }}/${payload.id}`;
      this.openEditModal = true;
    },

    openDelete(payload){
      this.del = { ...payload };
      this.deleteAction = `{{ url('/superadmin/users') }}/${payload.id}`;
      this.openDeleteModal = true;
    },
  }));
});
</script>
@endpush
@endsection
