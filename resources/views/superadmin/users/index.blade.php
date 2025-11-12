{{-- resources/views/superadmin/users/index.blade.php --}}
@extends('layouts.superadmin')

@section('title','Superadmin — Users')

@section('content')
<style>[x-cloak]{display:none!important}</style>

<div
  x-data="usersPage"
  x-init="init()"
  class="space-y-6"
  {{-- Penting: data-* untuk pre-check & flash --}}
  data-check-url="{{ route('superadmin.users.check-unique') }}"
  data-users-index-url="{{ route('superadmin.users.index') }}"
  data-flash-ok="{{ session('ok') }}"
  data-flash-err="{{ session('err') }}"
  data-errors='@json($errors->any() ? $errors->all() : [])'
>
  {{-- Toast modern --}}
  <div class="fixed bottom-4 right-4 z-[60] space-y-3" x-cloak x-show="toasts.length">
    <template x-for="t in toasts" :key="t.id">
      <div x-transition
           class="max-w-sm rounded-xl ring-1 ring-black/10 shadow-lg p-4 bg-white flex gap-3">
        <div>
          <template x-if="t.type==='success'">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-700">✓</span>
          </template>
          <template x-if="t.type==='error'">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-700">!</span>
          </template>
          <template x-if="t.type==='info'">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700">i</span>
          </template>
        </div>
        <div class="text-sm text-gray-800" x-text="t.message"></div>
        <button class="ml-auto text-gray-400 hover:text-gray-600" @click="dismissToast(t.id)">✕</button>
      </div>
    </template>
  </div>

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
          <a href="{{ route('superadmin.users.index') }}"
             class="px-3 py-2 rounded-xl ring-1 ring-gray-200 text-gray-700 bg-white hover:bg-gray-50 text-sm">Reset</a>
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
              <td class="p-3 text-gray-700 break-all">{{ $u->email }}</td>
              <td class="p-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs ring-1 {{ $roleClasses }}">
                  {{ ucfirst($roleName) }}
                </span>
              </td>
              <td class="p-3 text-right font-semibold text-gray-900 whitespace-nowrap">
                Rp {{ number_format((int)($u->donated_sum ?? 0), 0, ',', '.') }}
              </td>
              <td class="p-3 text-gray-700">
                {{ number_format((int)($u->donated_cnt ?? 0)) }}
              </td>
              <td class="p-3">
                <div class="flex items-center justify-end gap-1.5">
                  <a href="{{ route('superadmin.users.show', $u) }}"
                     class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg ring-1 ring-gray-200 hover:bg-gray-50"
                     title="Detail user">
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
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                      <path d="M4 21l4.5-1 10-10a2.1 2.1 0 10-3-3l-10 10L4 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="hidden lg:inline">Edit</span>
                  </button>

                  <button
                    @click="openDelete({ id: {{ $u->id }}, name: @js($u->name) })"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700"
                    title="Hapus user">
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

  {{-- ===== Create Modal ===== --}}
  <div x-cloak x-show="openCreate" x-transition
       class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-4">
    <div @click.away="openCreate=false"
         class="w-full max-w-xl rounded-3xl bg-white/90 backdrop-blur ring-1 ring-gray-200 shadow-2xl overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div>
          <h3 class="text-lg font-semibold text-gray-900">Tambah User</h3>
          <p class="text-xs text-gray-500">Lengkapi data pengguna dan tentukan perannya.</p>
        </div>
        <button @click="openCreate=false"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl hover:bg-gray-100 text-gray-500"
                aria-label="Tutup modal">✕</button>
      </div>

      <form
        method="post"
        action="{{ route('superadmin.users.store') }}"
        x-data="createForm()"
        @submit.prevent.stop="doSubmit($event)"
        class="px-6 py-5 space-y-4"
      >
        @csrf

        {{-- Banner error UI --}}
        <div x-show="bannerError"
             x-transition
             class="rounded-xl bg-red-50 text-red-700 ring-1 ring-red-200 px-3 py-2 text-sm"
             x-text="bannerError"></div>

        <div>
          <label class="text-sm font-medium text-gray-800">Nama</label>
          <input name="name" required
                 class="mt-1 w-full rounded-xl border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                 placeholder="Nama lengkap">
          @error('name')
            <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <label class="text-sm font-medium text-gray-800">Email</label>
          <input type="email" name="email" required
                 class="mt-1 w-full rounded-xl border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                 placeholder="email@contoh.com">
          @error('email')
            <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
          @enderror
        </div>

        <div x-data="{ showPwd:false, showPwd2:false, pwd:'', strength:0 }" x-init="
             $watch('pwd', v => {
               let s=0;
               if(v.length>=8) s++;
               if(/[A-Z]/.test(v)) s++;
               if(/[a-z]/.test(v)) s++;
               if(/[0-9\W]/.test(v)) s++;
               strength=s;
             })
        ">
          <label class="text-sm font-medium text-gray-800">Password</label>
          <div class="mt-1 relative">
            <input :type="showPwd ? 'text' : 'password'"
                   x-model="pwd"
                   name="password" required
                   class="w-full rounded-xl border pl-3 pr-10 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                   placeholder="Minimal 8 karakter">
            <button type="button" @click="showPwd=!showPwd"
                    class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700">👁</button>
          </div>
          <div class="mt-2">
            <div class="h-2 w-full rounded-full bg-gray-100 overflow-hidden">
              <div class="h-2 rounded-full transition-all"
                   :class="strength<=1 ? 'bg-red-500' : (strength==2 ? 'bg-amber-500' : (strength==3 ? 'bg-lime-500' : 'bg-emerald-600'))"
                   :style="`width:${(strength/4)*100}%`"></div>
            </div>
          </div>

          <label class="mt-3 text-sm font-medium text-gray-800">Konfirmasi Password</label>
          <div class="mt-1 relative">
            <input :type="showPwd2 ? 'text' : 'password'"
                   name="password_confirmation" required
                   class="w-full rounded-xl border pl-3 pr-10 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                   placeholder="Ulangi password">
            <button type="button" @click="showPwd2=!showPwd2"
                    class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700">👁</button>
          </div>
        </div>

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

        <div class="pt-2 flex items-center justify-end gap-2">
          <button type="button" @click="openCreate=false"
                  class="px-4 py-2 rounded-xl ring-1 ring-gray-200 bg-white hover:bg-gray-50">Batal</button>
          <button
            :disabled="submitting"
            :class="submitting ? 'opacity-50 cursor-not-allowed' : ''"
            class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
            <span x-show="!submitting">Simpan User</span>
            <span x-show="submitting">Menyimpan…</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- ===== Edit Modal ===== --}}
  <div x-cloak x-show="openEditModal" x-transition
       class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-4">
    <div @click.away="openEditModal=false" class="w-full max-w-lg rounded-2xl bg-white ring-1 ring-gray-200 shadow-xl">
      <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-900">Edit User</h3>
        <button @click="openEditModal=false" class="text-gray-400 hover:text-gray-600">✕</button>
      </div>

      <form
        :action="editAction"
        method="post"
        x-data="editForm(() => edit)"
        @submit.prevent.stop="doSubmit($event)"
        class="p-5 space-y-4"
      >
        @csrf @method('PUT')

        {{-- Banner error UI --}}
        <div x-show="bannerError"
             x-transition
             class="rounded-xl bg-red-50 text-red-700 ring-1 ring-red-200 px-3 py-2 text-sm"
             x-text="bannerError"></div>

        <div>
          <label class="text-sm font-medium">Nama</label>
          <input name="name" x-model="state.name" required class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"/>
        </div>
        <div>
          <label class="text-sm font-medium">Email</label>
          <input type="email" name="email" x-model="state.email" required class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"/>
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
          <select name="role" x-model="state.role" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
            @foreach($allowedRoles as $r)
              <option value="{{ $r }}">{{ $r }}</option>
            @endforeach
          </select>
        </div>

        <div class="pt-2 flex items-center justify-end gap-2">
          <button type="button" @click="openEditModal=false" class="px-4 py-2 rounded-xl ring-1 ring-gray-200">Batal</button>
          <button
            :disabled="submitting"
            :class="submitting ? 'opacity-50 cursor-not-allowed' : ''"
            class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
            <span x-show="!submitting">Simpan</span>
            <span x-show="submitting">Menyimpan…</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- ===== Delete Modal ===== --}}
  <div x-cloak x-show="openDeleteModal" x-transition
       class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-4">
    <div @click.away="openDeleteModal=false" class="w-full max-w-md rounded-2xl bg-white ring-1 ring-gray-200 shadow-xl">
      <div class="p-5">
        <h3 class="font-semibold text-gray-900">Hapus User</h3>
        <p class="text-sm text-gray-600 mt-2">
          Yakin ingin menghapus user <span class="font-semibold" x-text="del.name"></span>? Tindakan ini tidak dapat dibatalkan.
        </p>
        <form :action="deleteAction" method="post" class="mt-4 flex items-center justify-end gap-2">
          @csrf @method('DELETE')
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
    openCreate:false,
    openEditModal:false,
    openDeleteModal:false,

    edit:{ id:null, name:'', email:'', role:'' },
    del: { id:null, name:'' },

    editAction:'#',
    deleteAction:'#',

    toasts:[],
    toastId:0,

    checkUrl:'',
    usersIndexUrl:'',

    init(){
      const ds = this.$root.dataset || {};
      this.checkUrl      = ds.checkUrl || '';
      this.usersIndexUrl = ds.usersIndexUrl || '';
      const flashOk  = ds.flashOk || '';
      const flashErr = ds.flashErr || '';
      const errors   = safeJson(ds.errors, []);

      if (flashOk)  this.pushToast('success', flashOk);
      if (flashErr) this.pushToast('error',   flashErr);
      if (Array.isArray(errors)) errors.forEach(m => this.pushToast('error', m));
    },

    pushToast(type, message){
      const id = ++this.toastId;
      this.toasts.push({ id, type, message });
      setTimeout(() => this.dismissToast(id), 4500);
    },
    dismissToast(id){ this.toasts = this.toasts.filter(t => t.id !== id); },

    openEdit(payload){
      this.edit = { ...payload };
      this.editAction = `${this.usersIndexUrl}/${payload.id}`;
      this.openEditModal = true;
    },
    openDelete(payload){
      this.del = { ...payload };
      this.deleteAction = `${this.usersIndexUrl}/${payload.id}`;
      this.openDeleteModal = true;
    },
  }));

  // helpers
  window.safeJson = function (raw, fallback) {
    try { return JSON.parse(raw || ''); } catch { return fallback; }
  };

  // Create form handler
  window.createForm = () => ({
    submitting:false,
    bannerError:'',
    async doSubmit(evt){
      if (this.submitting) return;
      const formEl = evt.target;
      const root   = formEl.closest('[x-data="usersPage"]');
      const checkUrl = root?._x_dataStack?.[0]?.checkUrl || root?.__x?.$data?.checkUrl || '';

      const name  = formEl.querySelector('input[name="name"]').value.trim();
      const email = formEl.querySelector('input[name="email"]').value.trim().toLowerCase();

      if (checkUrl) {
        try{
          const url = new URL(checkUrl, window.location.origin);
          if (name)  url.searchParams.set('name', name);
          if (email) url.searchParams.set('email', email);
          const res = await fetch(url.toString(), { headers: { 'Accept':'application/json' } });
          const json = await res.json();

          if (json?.dupes?.email || json?.dupes?.name){
            this.bannerError = [
              json.dupes.name  ? 'Nama sudah terdaftar.' : null,
              json.dupes.email ? 'Email sudah terdaftar.' : null
            ].filter(Boolean).join(' ');
            (json.dupes.email
              ? formEl.querySelector('input[name="email"]')
              : formEl.querySelector('input[name="name"]')
            )?.focus();
            return; // block submit
          }
        }catch(e){
          console.warn('[check-unique] gagal', e);
        }
      }

      this.bannerError='';
      this.submitting=true;
      formEl.submit();
    }
  });

  // Edit form handler
  window.editForm = (getEditState) => ({
    submitting:false,
    bannerError:'',
    state:{ id:null, name:'', email:'', role:'' },
    init(){ this.state = { ...getEditState() }; },
    async doSubmit(evt){
      if (this.submitting) return;
      const formEl = evt.target;
      const root   = formEl.closest('[x-data="usersPage"]');
      const checkUrl = root?._x_dataStack?.[0]?.checkUrl || root?.__x?.$data?.checkUrl || '';

      const id    = this.state.id;
      const name  = formEl.querySelector('input[name="name"]').value.trim();
      const email = formEl.querySelector('input[name="email"]').value.trim().toLowerCase();

      if (checkUrl) {
        try{
          const url = new URL(checkUrl, window.location.origin);
          if (name)  url.searchParams.set('name', name);
          if (email) url.searchParams.set('email', email);
          if (id)    url.searchParams.set('ignore_id', String(id));
          const res = await fetch(url.toString(), { headers: { 'Accept':'application/json' } });
          const json = await res.json();

          if (json?.dupes?.email || json?.dupes?.name){
            this.bannerError = [
              json.dupes.name  ? 'Nama sudah terdaftar.' : null,
              json.dupes.email ? 'Email sudah terdaftar.' : null
            ].filter(Boolean).join(' ');
            (json.dupes.email
              ? formEl.querySelector('input[name="email"]')
              : formEl.querySelector('input[name="name"]')
            )?.focus();
            return; // block submit
          }
        }catch(e){
          console.warn('[check-unique] gagal', e);
        }
      }

      this.bannerError='';
      this.submitting=true;
      formEl.submit();
    }
  });
});
</script>
@endpush
@endsection
