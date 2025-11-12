@extends('layouts.admin')

@section('title','Admin — Users')

@section('content')
<style>[x-cloak]{display:none!important}</style>

@php
  $flashOk   = session('ok') ?? '';
  $flashErr  = session('err') ?? '';
  $errorsArr = $errors->any() ? $errors->all() : [];
  $formFlag  = session('form') ?? '';
  $editId    = session('edit_id') ?? '';
  $checkUrl  = route('admin.users.check-unique');
  $indexUrl  = route('admin.users.index');
@endphp

<div
  x-data="usersPage"
  x-init="init($el)"
  data-flash-ok="{{ e($flashOk) }}"
  data-flash-err="{{ e($flashErr) }}"
  data-errors='@json($errorsArr)'
  data-form-flag="{{ e($formFlag) }}"
  data-edit-id="{{ e((string)$editId) }}"
  data-check-url="{{ $checkUrl }}"
  data-users-index-url="{{ $indexUrl }}"
  class="space-y-6">

  {{-- ===== Center Alert (success/error) ===== --}}
  <div class="fixed inset-0 z-[70] flex items-center justify-center bg-black/40 p-4"
       x-cloak x-show="centerAlert.open" x-transition.opacity>
    <div @click.outside="centerAlert.open=false"
         class="w-full max-w-md rounded-2xl bg-white ring-1 ring-gray-200 shadow-2xl overflow-hidden">
      <div class="p-6 text-center">
        <template x-if="centerAlert.type==='success'">
          <div class="mx-auto mb-4 h-12 w-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl">✓</div>
        </template>
        <template x-if="centerAlert.type==='error'">
          <div class="mx-auto mb-4 h-12 w-12 rounded-full bg-red-100 text-red-700 flex items-center justify-center text-2xl">!</div>
        </template>
        <h3 class="text-lg font-semibold text-gray-900" x-text="centerAlert.title || (centerAlert.type==='success' ? 'Berhasil' : 'Gagal')"></h3>
        <p class="mt-2 text-sm text-gray-600" x-text="centerAlert.message"></p>
        <div class="mt-6 flex justify-center gap-2">
          <button @click="centerAlert.open=false"
                  class="px-4 py-2 rounded-xl ring-1 ring-gray-200 bg-white hover:bg-gray-50">Tutup</button>
          <template x-if="centerAlert.type==='success' && centerAlert.redirectUrl">
            <button @click="window.location.href=centerAlert.redirectUrl"
                    class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Lanjut</button>
          </template>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== Toasts ringan (opsional) ===== --}}
  <div class="fixed bottom-4 right-4 z-[60] space-y-3" x-cloak x-show="toasts.length">
    <template x-for="t in toasts" :key="t.id">
      <div x-transition class="max-w-sm rounded-xl ring-1 ring-black/10 shadow-lg p-4 bg-white flex gap-3">
        <div>
          <template x-if="t.type==='success'">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">✓</span>
          </template>
          <template x-if="t.type==='error'">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-700">!</span>
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
      <p class="text-gray-600">Kelola pengguna & lihat kumulasi donasi per user (Admin).</p>
    </div>

    <div class="flex items-center gap-2">
      <form method="get" class="flex items-center gap-2" action="{{ $indexUrl }}">
        <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama/email…"
               class="w-56 border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"/>
        <button class="px-3 py-2 rounded-xl bg-blue-600 text-white text-sm hover:bg-blue-700">Cari</button>
        @if($q !== '')
          <a href="{{ $indexUrl }}" class="px-3 py-2 rounded-xl ring-1 ring-gray-200 text-gray-700 bg-white hover:bg-gray-50 text-sm">Reset</a>
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
            @php $roleName = $u->getRoleNames()->first() ?? '—'; @endphp
            <tr class="hover:bg-gray-50/60">
              <td class="p-3">
                <div class="font-semibold text-gray-900">{{ $u->name }}</div>
                <div class="text-xs text-gray-500">#{{ $u->id }}</div>
              </td>
              <td class="p-3 text-gray-700 break-all">{{ $u->email }}</td>
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
      {{ $users->onEachSide(1)->links() }}
    </div>
  </div>

  {{-- ===== Create User Modal ===== --}}
  <div x-cloak x-show="openCreate" x-transition
       class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-4">
    <div @click.outside="openCreate=false"
         class="w-full max-w-xl rounded-3xl bg-white/90 backdrop-blur ring-1 ring-gray-200 shadow-2xl overflow-hidden">

      <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div>
          <h3 class="text-lg font-semibold text-gray-900">Tambah User</h3>
          <p class="text-xs text-gray-500">Lengkapi data & tentukan role.</p>
        </div>
        <button @click="openCreate=false" class="inline-flex h-9 w-9 items-center justify-center rounded-xl hover:bg-gray-100 text-gray-500">✕</button>
      </div>

      <form method="post" action="{{ route('admin.users.store') }}"
            x-data="createForm()"
            @submit.prevent.stop="doSubmit($event, $el)"
            class="px-6 py-5 space-y-4" novalidate>
        @csrf

        <template x-if="bannerError">
          <div class="rounded-xl bg-red-50 text-red-800 ring-1 ring-red-200 px-4 py-3 text-sm">
            <span x-text="bannerError"></span>
          </div>
        </template>

        <div>
          <label class="text-sm font-medium text-gray-800">Nama</label>
          <input name="name" x-ref="name" required
                 class="mt-1 w-full rounded-xl border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                 placeholder="Nama lengkap">
        </div>

        <div>
          <label class="text-sm font-medium text-gray-800">Email</label>
          <input type="email" name="email" x-ref="email" required
                 class="mt-1 w-full rounded-xl border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                 placeholder="email@contoh.com">
        </div>

        <div x-data="{showPwd:false, pwd:'', strength:0}"
             x-init="$watch('pwd', v => { let s=0; if(v.length>=8) s++; if(/[A-Z]/.test(v)) s++; if(/[a-z]/.test(v)) s++; if(/[0-9\W]/.test(v)) s++; strength=s; })">
          <label class="text-sm font-medium text-gray-800">Password</label>
          <div class="mt-1 relative">
            <input :type="showPwd ? 'text' : 'password'" x-model="pwd" name="password" required
                   class="w-full rounded-xl border pl-3 pr-10 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                   placeholder="Minimal 8 karakter">
            <button type="button" @click="showPwd=!showPwd"
                    class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700" aria-label="Toggle">👁</button>
          </div>
          <div class="mt-2">
            <div class="h-2 w-full rounded-full bg-gray-100 overflow-hidden">
              <div class="h-2 rounded-full transition-all"
                   :class="['bg-red-500','bg-amber-500','bg-lime-500','bg-emerald-600'][Math.max(0,strength-1)] || 'bg-red-500'"
                   :style="`width:${(strength/4)*100}%`"></div>
            </div>
            <div class="mt-1 flex items-center justify-between text-[11px] text-gray-500">
              <span>Kekuatan password</span>
              <span x-text="['Sangat lemah','Lemah','Cukup','Kuat','Sangat kuat'][strength] || '—'"></span>
            </div>
          </div>
        </div>

        <div>
          <label class="text-sm font-medium text-gray-800">Konfirmasi Password</label>
          <input type="password" name="password_confirmation" required
                 class="mt-1 w-full rounded-xl border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                 placeholder="Ulangi password">
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
          <button type="button" @click="openCreate=false"
                  class="px-4 py-2 rounded-xl ring-1 ring-gray-200 bg-white hover:bg-gray-50">Batal</button>
          <button :disabled="submitting" :class="submitting ? 'opacity-50 cursor-not-allowed' : ''"
                  class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
            Simpan User
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- ===== Edit Modal ===== --}}
  <div x-cloak x-show="openEditModal" x-transition
       class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-4">
    <div @click.outside="openEditModal=false" class="w-full max-w-lg rounded-2xl bg-white ring-1 ring-gray-200 shadow-xl">
      <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-900">Edit User</h3>
        <button @click="openEditModal=false" class="text-gray-400 hover:text-gray-600">✕</button>
      </div>

      <form :action="editAction" method="post"
            x-data="editForm(() => edit)"
            @submit.prevent.stop="doSubmit($event, $el)"
            class="p-5 space-y-4" novalidate>
        @csrf
        @method('PUT')

        <template x-if="bannerError">
          <div class="rounded-xl bg-red-50 text-red-800 ring-1 ring-red-200 px-4 py-3 text-sm">
            <span x-text="bannerError"></span>
          </div>
        </template>

        <div>
          <label class="text-sm font-medium">Nama</label>
          <input name="name" x-ref="name" x-model="state.name" required class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"/>
        </div>
        <div>
          <label class="text-sm font-medium">Email</label>
          <input type="email" name="email" x-ref="email" x-model="state.email" required class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"/>
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
          <button :disabled="submitting" :class="submitting ? 'opacity-50 cursor-not-allowed' : ''"
                  class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  {{-- ===== Delete Modal ===== --}}
  <div x-cloak x-show="openDeleteModal" x-transition
       class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-4">
    <div @click.outside="openDeleteModal=false" class="w-full max-w-md rounded-2xl bg-white ring-1 ring-gray-200 shadow-xl">
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
  // helper JSON aman
  window.safeJson = (raw, fallback) => { try { return JSON.parse(raw||''); } catch { return fallback; } };

  Alpine.data('usersPage', () => ({
    openCreate:false,
    openEditModal:false,
    openDeleteModal:false,

    edit:{ id:null, name:'', email:'', role:'' },
    del:{ id:null, name:'' },

    editAction:'#',
    deleteAction:'#',

    // toast ringan
    toasts:[], toastId:0,

    // alert tengah
    centerAlert:{ open:false, type:'success', title:'', message:'', redirectUrl:null },

    // services URL
    __checkUrl:null,
    __usersIndexUrl:null,

    init(rootEl){
      const root = rootEl || this.$el;

      this.__checkUrl      = root.dataset.checkUrl      || null;
      this.__usersIndexUrl = root.dataset.usersIndexUrl || '{{ url('/admin/users') }}';

      // flash → tampil di tengah
      const flashOk   = root.dataset.flashOk  || '';
      const flashErr  = root.dataset.flashErr || '';
      const errorsArr = safeJson(root.dataset.errors, []);
      const formFlag  = root.dataset.formFlag || '';
      const editId    = root.dataset.editId   || '';

      if (flashOk) this.showCenter('success', flashOk, 'Berhasil');
      if (flashErr) this.showCenter('error',   flashErr, 'Gagal');
      if (Array.isArray(errorsArr) && errorsArr.length) {
        this.showCenter('error', errorsArr.join(' '), 'Gagal');
      }

      // auto-buka modal sesuai flag dari controller
      if (formFlag === 'create') this.openCreate = true;
      if (formFlag === 'edit' && editId) {
        // minimal buka modal, datanya akan diisi saat klik edit; atau fetch detail jika perlu
        this.openEditModal = true;
      }
    },

    // helpers notif
    showCenter(type, message, title=null, redirectUrl=null){
      this.centerAlert = { open:true, type, title, message, redirectUrl };
    },
    pushToast(type,message){
      const id = ++this.toastId; this.toasts.push({id,type,message});
      setTimeout(()=>this.dismissToast(id), 4500);
    },
    dismissToast(id){ this.toasts = this.toasts.filter(t => t.id!==id); },

    // actions
    openEdit(payload){
      this.edit = { ...payload };
      this.editAction = `${this.__usersIndexUrl}/${payload.id}`;
      this.openEditModal = true;
    },
    openDelete(payload){
      this.del = { ...payload };
      this.deleteAction = `${this.__usersIndexUrl}/${payload.id}`;
      this.openDeleteModal = true;
    },
  }));

  // CREATE pre-check
  window.createForm = () => ({
    submitting:false, bannerError:'', _nativeBypass:false,
    async doSubmit(e, formEl){
      e?.preventDefault?.(); e?.stopPropagation?.(); e?.stopImmediatePropagation?.();
      if (this.submitting) return; this.submitting = true;

      const root = formEl.closest('[x-data="usersPage"]'); const usersPage = root?.__x?.$data;
      const checkUrl = usersPage?.__checkUrl || null;

      const nameInput  = formEl.querySelector('input[name="name"]');
      const emailInput = formEl.querySelector('input[name="email"]');
      const name  = (nameInput?.value || '').trim();
      const email = (emailInput?.value || '').trim().toLowerCase();

      try{
        if (checkUrl){
          const url = new URL(checkUrl, window.location.origin);
          if (name)  url.searchParams.set('name', name);
          if (email) url.searchParams.set('email', email);

          const res = await fetch(url.toString(), { headers:{'Accept':'application/json'} });
          const json = await res.json();
          const dupName = !!json?.dupes?.name, dupEmail = !!json?.dupes?.email;

          if (dupName || dupEmail){
            this.bannerError = [
              dupName  ? 'Nama sudah terdaftar.'  : null,
              dupEmail ? 'Email sudah terdaftar.' : null,
            ].filter(Boolean).join(' ');
            usersPage?.showCenter?.('error', this.bannerError, 'Data Duplikat');
            (dupEmail ? emailInput : nameInput)?.focus();
            this.submitting = false; return false;
          }
        }
        this.bannerError = ''; this._nativeBypass = true;
        HTMLFormElement.prototype.submit.call(formEl);
      }catch(err){
        console.warn('[admin.createForm] pre-check error', err);
        this.bannerError = 'Terjadi masalah saat memeriksa data. Coba lagi.';
        usersPage?.showCenter?.('error', this.bannerError, 'Gagal');
        this.submitting = false; return false;
      }
    }
  });

  // EDIT pre-check
  window.editForm = (getEditState) => ({
    submitting:false, bannerError:'', _nativeBypass:false,
    state:{ id:null, name:'', email:'', role:'' },
    init(){ this.state = { ...getEditState() }; },
    async doSubmit(e, formEl){
      e?.preventDefault?.(); e?.stopPropagation?.(); e?.stopImmediatePropagation?.();
      if (this.submitting) return; this.submitting = true;

      const root = formEl.closest('[x-data="usersPage"]'); const usersPage = root?.__x?.$data;
      const checkUrl = usersPage?.__checkUrl || null;

      const id = this.state.id;
      const nameInput  = formEl.querySelector('input[name="name"]');
      const emailInput = formEl.querySelector('input[name="email"]');
      const name  = (nameInput?.value || '').trim();
      const email = (emailInput?.value || '').trim().toLowerCase();

      try{
        if (checkUrl){
          const url = new URL(checkUrl, window.location.origin);
          if (name)  url.searchParams.set('name', name);
          if (email) url.searchParams.set('email', email);
          if (id)    url.searchParams.set('ignore_id', String(id));

          const res = await fetch(url.toString(), { headers:{'Accept':'application/json'} });
          const json = await res.json();
          const dupName = !!json?.dupes?.name, dupEmail = !!json?.dupes?.email;

          if (dupName || dupEmail){
            this.bannerError = [
              dupName  ? 'Nama sudah terdaftar.'  : null,
              dupEmail ? 'Email sudah terdaftar.' : null,
            ].filter(Boolean).join(' ');
            usersPage?.showCenter?.('error', this.bannerError, 'Data Duplikat');
            (dupEmail ? emailInput : nameInput)?.focus();
            this.submitting = false; return false;
          }
        }
        this.bannerError = ''; this._nativeBypass = true;
        HTMLFormElement.prototype.submit.call(formEl);
      }catch(err){
        console.warn('[admin.editForm] pre-check error', err);
        this.bannerError = 'Terjadi masalah saat memeriksa data. Coba lagi.';
        usersPage?.showCenter?.('error', this.bannerError, 'Gagal');
        this.submitting = false; return false;
      }
    }
  });
});
</script>
@endpush
@endsection
