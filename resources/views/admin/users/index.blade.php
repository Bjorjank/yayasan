@extends('layouts.admin')

@section('title','Admin — Users')

@section('content')
<style>[x-cloak]{display:none!important}</style>

<div x-data="usersPage" x-init="init()" class="space-y-6">

  {{-- Header & actions --}}
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <h1 class="text-2xl md:text-3xl font-black text-gray-900">Users</h1>
      <p class="text-gray-600">Kelola pengguna & lihat kumulasi donasi per user (Admin).</p>
    </div>

    <div class="flex items-center gap-2">
      <form method="get" class="flex items-center gap-2">
        <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama/email…"
               class="w-56 border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"/>
        <button class="px-3 py-2 rounded-xl bg-blue-600 text-white text-sm hover:bg-blue-700">Cari</button>
        @if($q !== '')
          <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-xl ring-1 ring-gray-200 text-gray-700 bg-white hover:bg-gray-50 text-sm">Reset</a>
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
    <div class="px-5 py-4 border-b border-gray-100 text-sm text-gray-600">
      Menampilkan <span class="font-semibold">{{ $users->count() }}</span> dari
      <span class="font-semibold">{{ $users->total() }}</span> pengguna
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
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
            @endphp
            <tr class="hover:bg-gray-50/60">
              <td class="p-3">
                <div class="font-semibold text-gray-900">{{ $u->name }}</div>
                <div class="text-xs text-gray-500">#{{ $u->id }}</div>
              </td>
              <td class="p-3 text-gray-700">{{ $u->email }}</td>
              <td class="p-3">
                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs ring-1 ring-gray-200 bg-gray-50 text-gray-700">
                  {{ $roleName }}
                </span>
              </td>
              <td class="p-3 text-right font-semibold text-gray-900">
                Rp {{ number_format((int)($u->donated_sum ?? 0),0,',','.') }}
              </td>
              <td class="p-3 text-gray-700">
                {{ number_format((int)($u->donated_cnt ?? 0)) }}
              </td>
              <td class="p-3">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.users.show', $u) }}"
                     class="px-3 py-1.5 rounded-lg ring-1 ring-gray-200 hover:bg-gray-50">Detail</a>

                  <button @click="openEdit({ id: {{ $u->id }},
                                               name: @js($u->name),
                                               email: @js($u->email),
                                               role: @js($roleName) })"
                          class="px-3 py-1.5 rounded-lg ring-1 ring-gray-200 hover:bg-gray-50">Edit</button>

                  <button @click="openDelete({ id: {{ $u->id }}, name: @js($u->name) })"
                          class="px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700">Hapus</button>
                </div>
              </td>
            </tr>
          @empty
            <tr><td class="p-6 text-center text-gray-500" colspan="6">Belum ada user.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="px-5 py-4 border-t border-gray-100">
      {{ $users->links() }}
    </div>
  </div>

  {{-- ===== Create User Modal ===== --}}
  <div x-cloak x-show="openCreate" x-transition
       class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-4">
    <div @click.away="openCreate=false"
         class="w-full max-w-xl rounded-3xl bg-white/90 backdrop-blur ring-1 ring-gray-200 shadow-2xl overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div>
          <h3 class="text-lg font-semibold text-gray-900">Tambah User</h3>
          <p class="text-xs text-gray-500">Lengkapi data & tentukan role.</p>
        </div>
        <button @click="openCreate=false" class="inline-flex h-9 w-9 items-center justify-center rounded-xl hover:bg-gray-100 text-gray-500">✕</button>
      </div>

      <form method="post" action="{{ route('admin.users.store') }}"
            x-data="{
              showPwd:false, showPwd2:false,
              pwd:'', pwd2:'', strength:0,
              level(){ let s=0; if(this.pwd.length>=8) s++; if(/[A-Z]/.test(this.pwd)) s++; if(/[a-z]/.test(this.pwd)) s++; if(/[0-9\\W]/.test(this.pwd)) s++; this.strength=s; return s; },
              barWidth(){ return `${(this.level()/4)*100}%`; },
              barColor(){ return this.strength<=1 ? 'bg-red-500' : this.strength==2 ? 'bg-amber-500' : this.strength==3 ? 'bg-lime-500' : 'bg-emerald-600'; }
            }"
            class="px-6 py-5 space-y-4">
        @csrf

        <div>
          <label class="text-sm font-medium text-gray-800">Nama</label>
          <input name="name" required class="mt-1 w-full rounded-xl border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none" placeholder="Nama lengkap">
        </div>

        <div>
          <label class="text-sm font-medium text-gray-800">Email</label>
          <input type="email" name="email" required class="mt-1 w-full rounded-xl border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none" placeholder="email@contoh.com">
        </div>

        <div>
          <label class="text-sm font-medium text-gray-800">Password</label>
          <div class="mt-1 relative">
            <input :type="showPwd ? 'text' : 'password'" x-model="pwd" name="password" required
                   class="w-full rounded-xl border pl-3 pr-10 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                   placeholder="Minimal 8 karakter" @input="level()">
            <button type="button" @click="showPwd=!showPwd"
                    class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700" aria-label="Toggle password">👁</button>
          </div>
          <div class="mt-2">
            <div class="h-2 w-full rounded-full bg-gray-100 overflow-hidden">
              <div class="h-2 rounded-full transition-all" :class="barColor()" :style="`width:${barWidth()}`"></div>
            </div>
            <div class="mt-1 flex items-center justify-between text-[11px] text-gray-500">
              <span>Kekuatan password</span>
              <span x-text="['Sangat lemah','Lemah','Cukup','Kuat','Sangat kuat'][strength] || '—'"></span>
            </div>
          </div>
        </div>

        <div>
          <label class="text-sm font-medium text-gray-800">Konfirmasi Password</label>
          <div class="mt-1 relative">
            <input :type="showPwd2 ? 'text' : 'password'" x-model="pwd2" name="password_confirmation" required
                   class="w-full rounded-xl border pl-3 pr-10 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                   placeholder="Ulangi password">
            <button type="button" @click="showPwd2=!showPwd2"
                    class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700" aria-label="Toggle password">👁</button>
          </div>
        </div>

        <div>
          <label class="text-sm font-medium text-gray-800">Role</label>
          <select name="role" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
            @foreach($allowedRoles as $r)
              <option value="{{ $r }}">{{ $r }}</option>
            @endforeach
          </select>
        </div>

        <div class="pt-2 flex items-center justify-end gap-2">
          <button type="button" @click="openCreate=false" class="px-4 py-2 rounded-xl ring-1 ring-gray-200 bg-white hover:bg-gray-50">Batal</button>
          <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Simpan User</button>
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
      this.editAction = `{{ url('/admin/users') }}/${payload.id}`;
      this.openEditModal = true;
    },
    openDelete(payload){
      this.del = { ...payload };
      this.deleteAction = `{{ url('/admin/users') }}/${payload.id}`;
      this.openDeleteModal = true;
    },
  }));
});
</script>
@endpush
@endsection
