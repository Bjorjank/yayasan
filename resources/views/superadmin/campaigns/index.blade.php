{{-- resources/views/superadmin/campaigns/index.blade.php --}}
@extends('layouts.superadmin')

@section('content')
<style>[x-cloak]{display:none!important}</style>

<div class="max-w-7xl mx-auto px-6 py-10"
     x-data="campaignAdmin"
     x-init="init({
        flashOk: @js(session('ok')),
        flashErrors: @js($errors->any() ? $errors->all() : []),
     })">

  {{-- Toasts (modern) --}}
  <div class="fixed bottom-4 right-4 z-[60] space-y-3"
       x-cloak
       x-show="toasts.length">
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

  {{-- Header --}}
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Campaigns</h1>
      <p class="text-gray-600">Kelola program donasi yayasan.</p>
    </div>
    <div class="flex items-center gap-3">
      <form method="get" class="flex items-center gap-2">
        <input
          type="text"
          name="q"
          value="{{ request('q') }}"
          placeholder="Cari judul…"
          class="w-64 border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
        />
        <button class="px-3 py-2 rounded-xl bg-blue-600 text-white text-sm hover:bg-blue-700">Cari</button>
      </form>
      <button type="button" @click="openCreate()"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"/></svg>
        Buat Campaign
      </button>
    </div>
  </div>

  {{-- Table card --}}
  <div class="mt-6 overflow-hidden rounded-2xl ring-1 ring-gray-200 bg-white">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
      <div class="text-sm text-gray-600">
        Menampilkan <span class="font-semibold">{{ $items->count() }}</span> dari
        <span class="font-semibold">{{ $items->total() }}</span> campaign
      </div>
      <div class="text-xs text-gray-500">Diperbarui: {{ now()->format('d M Y H:i') }}</div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
          <tr>
            <th class="p-4 text-left">Cover</th>
            <th class="p-4 text-left">Judul</th>
            <th class="p-4 text-left">Status</th>
            <th class="p-4 text-right">Target</th>
            <th class="p-4 text-right">Deadline</th>
            <th class="p-4"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse ($items as $c)
          @php
            $coverUrl = null;
            if ($c->cover_url) {
              $isAbs = \Illuminate\Support\Str::startsWith($c->cover_url, ['http://','https://','//']);
              $coverUrl = $isAbs ? $c->cover_url : asset('storage/'.$c->cover_url);
            }
          @endphp
          <tr class="hover:bg-gray-50/60">
            <td class="p-4">
              @if ($coverUrl)
                <img src="{{ $coverUrl }}" alt="cover" class="h-12 w-12 rounded object-cover ring-1 ring-gray-200">
              @else
                <div class="h-12 w-12 rounded bg-gray-100 grid place-items-center text-gray-400 text-xs">N/A</div>
              @endif
            </td>
            <td class="p-4">
              <div class="font-medium text-gray-900">{{ $c->title }}</div>
              <div class="text-xs text-gray-500">/{{ $c->slug }}</div>
            </td>
            <td class="p-4">
              @php
                $map = [
                  'draft' => 'bg-gray-100 text-gray-700 ring-gray-200',
                  'published' => 'bg-green-100 text-green-800 ring-green-200',
                  'closed' => 'bg-amber-100 text-amber-800 ring-amber-200',
                ];
                $badge = $map[$c->status] ?? 'bg-gray-100 text-gray-700 ring-gray-200';
              @endphp
              <span class="inline-flex px-2.5 py-1 rounded-full text-xs ring-1 {{ $badge }}">{{ strtoupper($c->status) }}</span>
            </td>
            <td class="p-4 text-right">Rp {{ number_format((int)$c->target_amount,0,',','.') }}</td>
            <td class="p-4 text-right text-gray-600">
              {{ optional($c->deadline_at)->format('d M Y H:i') ?? '—' }}
            </td>
            <td class="p-4">
              <div class="flex justify-end gap-3">
                <a href="{{ route('campaign.show', $c) }}" target="_blank"
                   class="text-blue-600 hover:text-blue-800">Lihat</a>

                <button type="button"
                        class="text-gray-700 hover:text-gray-900"
                        @click="openEdit({
                          id: {{ $c->id }},
                          title: @js($c->title),
                          slug: @js($c->slug),
                          goal_amount: {{ (int)$c->target_amount }},
                          deadline_at: @js(optional($c->deadline_at)->format('Y-m-d\\TH:i')),
                          status: @js($c->status),
                          category: @js($c->category),
                          description: @js($c->description),
                          cover_url: @js($coverUrl),
                          update_url: @js(route('superadmin.campaigns.update', $c))
                        })">Edit</button>

                <button type="button"
                        class="text-red-600 hover:text-red-800"
                        @click="openDelete({
                          id: {{ $c->id }},
                          title: @js($c->title),
                          slug: @js($c->slug),
                          action: @js(route('superadmin.campaigns.destroy', $c))
                        })">Hapus</button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="p-6 text-center text-gray-500">Belum ada campaign.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="px-5 py-4 border-t border-gray-100">
      {{ $items->withQueryString()->links() }}
    </div>
  </div>

  {{-- ========== MODAL CREATE ========== --}}
  <div x-cloak x-show="showCreate" x-transition.opacity
       class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
       @keydown.window.escape="showCreate=false"
       @click.self="showCreate=false">
    <div class="w-full max-w-2xl rounded-2xl bg-white ring-1 ring-gray-200 shadow-xl">
      <div class="flex items-center justify-between px-6 py-4 border-b">
        <h2 class="text-lg font-semibold">Buat Campaign</h2>
        <button type="button" class="text-gray-500 hover:text-gray-700" @click="showCreate=false" aria-label="Tutup">✕</button>
      </div>

      <form method="post" action="{{ route('superadmin.campaigns.store') }}" class="p-6 space-y-4"
            enctype="multipart/form-data" @submit="onCreateSubmit">
        @csrf

        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="text-sm font-medium text-gray-700">Judul</label>
            <input name="title" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm" required x-ref="createTitle">
          </div>
          <div>
            <label class="text-sm font-medium text-gray-700">Slug (opsional)</label>
            <input name="slug" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
          </div>
        </div>

        <div>
          <label class="text-sm font-medium text-gray-700">Kategori (opsional)</label>
          <input name="category" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
        </div>

        <div>
          <label class="text-sm font-medium text-gray-700">Deskripsi</label>
          <textarea name="description" rows="5" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"></textarea>
        </div>

        <div class="grid md:grid-cols-3 gap-4 items-end">
          <div>
            <label class="text-sm font-medium text-gray-700">Target (Rp)</label>
            <input
              type="text"
              inputmode="numeric"
              x-model="create.goal_amount_view"
              @input="formatGoalInput('create')"
              placeholder="cth: 10.000.000"
              class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"
              required
            >
            <input type="hidden" name="goal_amount" :value="create.goal_amount_raw">
          </div>

          <div>
            <label class="text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm" required>
              <option value="draft">draft</option>
              <option value="published">published</option>
              <option value="closed">closed</option>
            </select>
          </div>

          <div class="md:col-span-1">
            <label class="text-sm font-medium text-gray-700">Deadline</label>
            <input type="datetime-local" name="deadline_at" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
          </div>
        </div>

        <div>
          <label class="text-sm font-medium text-gray-700">Cover (gambar, maks 5MB)</label>
          <input type="file" name="cover" accept="image/*"
                 class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"
                 x-ref="createCover">
          <p class="mt-1 text-xs text-gray-500">JPEG/PNG/WebP, maksimal 5MB.</p>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
          <button type="button" class="px-4 py-2 rounded-xl border text-gray-700" @click="showCreate=false">Batal</button>
          <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  {{-- ========== MODAL EDIT ========== --}}
  <div x-cloak x-show="showEdit" x-transition.opacity
       class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
       @keydown.window.escape="showEdit=false"
       @click.self="showEdit=false">
    <div class="w-full max-w-2xl rounded-2xl bg-white ring-1 ring-gray-200 shadow-xl">
      <div class="flex items-center justify-between px-6 py-4 border-b">
        <h2 class="text-lg font-semibold">Edit Campaign</h2>
        <button type="button" class="text-gray-500 hover:text-gray-700" @click="showEdit=false" aria-label="Tutup">✕</button>
      </div>

      <form :action="edit.update_url" method="post" class="p-6 space-y-4" enctype="multipart/form-data" @submit="onEditSubmit">
        @csrf @method('PUT')

        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="text-sm font-medium text-gray-700">Judul</label>
            <input name="title" x-model="edit.title" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm" required x-ref="editTitle">
          </div>
          <div>
            <label class="text-sm font-medium text-gray-700">Slug (opsional)</label>
            <input name="slug" x-model="edit.slug" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
          </div>
        </div>

        <div>
          <label class="text-sm font-medium text-gray-700">Kategori (opsional)</label>
          <input name="category" x-model="edit.category" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
        </div>

        <div>
          <label class="text-sm font-medium text-gray-700">Deskripsi</label>
          <textarea name="description" rows="5" x-model="edit.description" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"></textarea>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <label class="text-sm font-medium text-gray-700">Target (Rp)</label>
            <input type="number" name="goal_amount" min="0" x-model.number="edit.goal_amount" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm" required>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-700">Deadline</label>
            <input type="datetime-local" name="deadline_at" x-model="edit.deadline_at" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
          </div>
          <div>
            <label class="text-sm font-medium text-gray-700">Status</label>
            <select name="status" x-model="edit.status" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm" required>
              <option value="draft">draft</option>
              <option value="published">published</option>
              <option value="closed">closed</option>
            </select>
          </div>
        </div>

        <div>
          <label class="text-sm font-medium text-gray-700">Cover saat ini</label>
          <template x-if="edit.cover_url">
            <img :src="edit.cover_url" alt="cover" class="mt-1 h-24 w-24 rounded object-cover ring-1 ring-gray-200">
          </template>
          <p class="mt-1 text-xs text-gray-500">Unggah cover baru (opsional, maks 5MB):</p>
          <input type="file" name="cover" accept="image/*"
                 class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
          <button type="button" class="px-4 py-2 rounded-xl border text-gray-700" @click="showEdit=false">Batal</button>
          <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  {{-- ========== MODAL DELETE (modern confirm) ========== --}}
  <div x-cloak x-show="showDelete" x-transition.opacity
       class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
       @keydown.window.escape="showDelete=false"
       @click.self="showDelete=false">
    <div class="w-full max-w-md rounded-2xl bg-white ring-1 ring-gray-200 shadow-xl">
      <div class="px-6 pt-6">
        <div class="flex items-start gap-3">
          <div class="h-10 w-10 rounded-full bg-red-100 text-red-700 grid place-items-center">!</div>
          <div>
            <h3 class="text-lg font-semibold">Hapus campaign?</h3>
            <p class="mt-1 text-sm text-gray-600">
              Kamu akan menghapus <span class="font-medium" x-text="del.title"></span>
              (<span class="text-gray-500" x-text="'/'+del.slug"></span>).
              Tindakan ini tidak dapat dibatalkan.
            </p>
          </div>
        </div>
      </div>
      <div class="px-6 pb-6 pt-4 flex justify-end gap-3">
        <button class="px-4 py-2 rounded-xl border text-gray-700" @click="showDelete=false">Batal</button>
        <form :action="del.action" method="post" x-ref="deleteForm">
          @csrf @method('DELETE')
          <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700">Hapus</button>
        </form>
      </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('campaignAdmin', () => ({
    // ====== STATE ======
    showCreate:false,
    showEdit:false,
    showDelete:false,

    toasts: [], // {id, type, message}
    toastId: 0,

    // create/edit/delete payloads
    create: { goal_amount_view:'', goal_amount_raw:'' },
    edit: {
      id:null, title:'', slug:'', category:'', description:'',
      goal_amount:0, deadline_at:'', status:'draft',
      cover_url:'', update_url:''
    },
    del: { id:null, title:'', slug:'', action:'' },

    // ====== INIT ======
    init({flashOk=null, flashErrors=[]}={}){
      // flash ke toast
      if (flashOk) this.pushToast('success', flashOk);
      if (Array.isArray(flashErrors)) {
        flashErrors.forEach(msg => this.pushToast('error', msg));
      }
      console.log('[campaignAdmin] init; __CHAT_DEBUG__=', window.__CHAT_DEBUG__);
    },

    // ====== TOASTS ======
    pushToast(type, message){
      const id = ++this.toastId;
      this.toasts.push({ id, type, message });
      setTimeout(() => this.dismissToast(id), 4000);
    },
    dismissToast(id){ this.toasts = this.toasts.filter(t => t.id !== id); },

    // ====== HELPERS ======
    _stripNonDigits(s){ return String(s||'').replace(/[^\d]/g,''); },
    _formatDotThousands(numStr){
      if(!numStr) return '';
      return numStr.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },
    formatGoalInput(scope){
      const raw = this._stripNonDigits(this[scope].goal_amount_view);
      this[scope].goal_amount_raw = raw;
      this[scope].goal_amount_view = this._formatDotThousands(raw);
    },

    // ====== HANDLERS ======
    openCreate(){
      this.create.goal_amount_view=''; this.create.goal_amount_raw='';
      this.showCreate = true;
      this.$nextTick(() => this.$refs.createTitle?.focus());
    },
    openEdit(data){
      const base = {
        id:null, title:'', slug:'', category:'', description:'',
        goal_amount:0, deadline_at:'', status:'draft',
        cover_url:'', update_url:''
      };
      this.edit = Object.assign(base, data || {});
      if (!this.edit.update_url) console.warn('[openEdit] update_url kosong.');
      this.showEdit = true;
      this.$nextTick(() => this.$refs.editTitle?.focus());
    },
    openDelete(data){
      this.del = Object.assign({id:null, title:'', slug:'', action:''}, data || {});
      this.showDelete = true;
    },

    // submit hooks
    onCreateSubmit(e){
      const fd = new FormData(e.target);
      if(!String(fd.get('title')||'').trim()){ e.preventDefault(); this.pushToast('error','Judul wajib diisi.'); return; }
      if(!String(fd.get('status')||'').trim()){ e.preventDefault(); this.pushToast('error','Status wajib dipilih.'); return; }
      if(!this.create.goal_amount_raw){
        this.create.goal_amount_raw = this._stripNonDigits(this.create.goal_amount_view);
      }
      const hidden = e.target.querySelector('input[name="goal_amount"]');
      if (hidden) hidden.value = this.create.goal_amount_raw || '0';

      const f = this.$refs.createCover?.files?.[0];
      if (f){
        const max = 5 * 1024 * 1024;
        if (f.size > max){ e.preventDefault(); this.pushToast('error','Ukuran cover melebihi 5MB.'); }
        if (!/^image\//.test(f.type)){ e.preventDefault(); this.pushToast('error','File cover harus berupa gambar.'); }
      }
    },
    onEditSubmit(e){
      if(!String(this.edit.title||'').trim()){ e.preventDefault(); this.pushToast('error','Judul wajib diisi.'); return; }
      if(!String(this.edit.status||'').trim()){ e.preventDefault(); this.pushToast('error','Status wajib dipilih.'); return; }
    },
  }));
});
</script>
@endpush
