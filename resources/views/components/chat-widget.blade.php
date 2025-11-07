{{-- resources/views/components/chat-widget.blade.php --}}
@php
    use App\Models\ChatMessage;

    // Default room buat bootstrap awal (boleh diubah di config/chat.php)
    $roomId = (int) (config('chat.default_room_id') ?? 1);
    $take   = (int) (config('chat.initial_take') ?? 50);

    $initialMessages = collect();
    if (auth()->check()) {
        $initialMessages = ChatMessage::where('room_id', $roomId)
            ->latest()->limit($take)->get()->reverse();
    }

    $initialPayload = $initialMessages->map(function ($m) {
        return [
            'id'         => (int) $m->id,
            'sender_id'  => (int) $m->sender_id,
            'message'    => (string) $m->message,
            'created_at' => optional($m->created_at)->toIso8601String(),
        ];
    })->values()->all();

    $chatConfig = [
        'roomId'  => $roomId,
        'initial' => $initialPayload,
        'me'      => auth()->id(),
    ];
@endphp

<div
  x-data='chatWidget(@json($chatConfig))'
  x-init="init()"
  class="fixed z-[9999] bottom-4 right-4"
>
  {{-- Floating Action Button (FAB) --}}
  <button
    @click="open = !open; if(open){ $nextTick(()=>scrollToBottom()) }"
    class="relative rounded-full shadow-lg bg-blue-600 text-white w-14 h-14 flex items-center justify-center hover:bg-blue-700 focus:outline-none"
    aria-label="Open chat"
    title="Chat"
  >
    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
      <path d="M20 2H4a2 2 0 00-2 2v18l4-4h14a2 2 0 002-2V4a2 2 0 00-2-2z"/>
    </svg>
    <template x-if="unread > 0">
      <span class="absolute -top-1 -right-1 text-xs bg-red-600 text-white rounded-full px-1.5 py-0.5" x-text="unread"></span>
    </template>
  </button>

  {{-- PANEL --}}
  <div
    x-show="open"
    x-transition
    class="mt-3 w-80 sm:w-96 rounded-2xl shadow-xl border bg-white overflow-hidden"
  >
    {{-- Header + Tabs --}}
    <div class="px-4 pt-3 bg-gray-50 border-b">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-xs text-gray-500">Chat Yayasan</div>
          <div class="font-semibold" x-text="mode==='chats' ? roomTitle : 'Cari User'"></div>
        </div>
        <div class="flex items-center gap-2">
          <!-- Toggle debug -->
          <button @click="showDebug = !showDebug"
                  class="text-[11px] px-2 py-1 border rounded hover:bg-gray-100"
                  :class="showDebug ? 'border-blue-500 text-blue-600' : 'border-gray-300 text-gray-600'">
            Debug
          </button>
          <button @click="open=false" class="text-gray-500 hover:text-gray-700" aria-label="Close">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="mt-2 grid grid-cols-2 gap-1">
        <button @click="switchMode('chats')"
                :class="mode==='chats' ? 'bg-white' : 'bg-gray-100'"
                class="py-1.5 rounded-lg text-sm border">
          Chats
        </button>
        <button @click="switchMode('users')"
                :class="mode==='users' ? 'bg-white' : 'bg-gray-100'"
                class="py-1.5 rounded-lg text-sm border">
          Users
        </button>
      </div>

      {{-- Search (Users mode only) + Autocomplete --}}
      <div class="mt-2 relative" x-show="mode==='users'">
        <input
          x-model="userQuery"
          @input="onSearchInput"
          @focus="onSearchFocus"
          @keydown.arrow-down.prevent="moveSel(1)"
          @keydown.arrow-up.prevent="moveSel(-1)"
          @keydown.enter.prevent="selectSel()"
          type="text"
          class="w-full border rounded-lg px-3 py-2 text-sm"
          placeholder="Cari nama/email…"
          autocomplete="off"
        >
        {{-- Dropdown --}}
        <div
          x-show="suggestOpen"
          x-transition
          class="absolute z-50 mt-1 w-full bg-white border rounded-lg shadow"
        >
          <template x-if="usersLoading">
            <div class="px-3 py-2 text-sm text-gray-500">Mencari…</div>
          </template>

          <template x-if="!usersLoading && users.length===0">
            <div class="px-3 py-2 text-sm text-gray-500">Tidak ada hasil.</div>
          </template>

          <template x-for="(u, idx) in users" :key="u.id">
            <button
              type="button"
              class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50"
              :class="idx === selIndex ? 'bg-blue-50' : ''"
              @mouseenter="selIndex = idx"
              @mouseleave="selIndex = -1"
              @click="selectUser(u)"
            >
              <div class="font-medium" x-text="u.name"></div>
              <div class="text-xs text-gray-500" x-text="u.email"></div>
            </button>
          </template>
        </div>
      </div>
    </div>

    {{-- Body --}}
    <div class="h-96 overflow-y-auto px-4 py-3 bg-white" id="chat-scroll-box">
      @auth
        {{-- USERS MODE (list di bawah, selain dropdown) --}}
        <template x-if="mode==='users'">
          <div class="pt-1">
            <template x-if="!suggestOpen && users.length===0 && !usersLoading">
              <div class="text-sm text-gray-500">Ketik untuk mencari user…</div>
            </template>

            <template x-if="!suggestOpen && users.length>0">
              <div>
                <div class="text-xs text-gray-500 mb-1">Hasil</div>
                <template x-for="u in users" :key="u.id">
                  <button
                    @click="selectUser(u)"
                    class="w-full text-left py-2 border-b last:border-b-0 hover:bg-gray-50"
                  >
                    <div class="font-medium" x-text="u.name"></div>
                    <div class="text-xs text-gray-500" x-text="u.email"></div>
                  </button>
                </template>
              </div>
            </template>
          </div>
        </template>

        {{-- CHATS MODE --}}
        <template x-if="mode==='chats'">
          <div>
            <template x-if="messages.length === 0">
              <div class="text-sm text-gray-500">Belum ada pesan. Mulai percakapan!</div>
            </template>

            <template x-for="m in messages" :key="m.id">
              <div class="mb-3" :class="m.sender_id === me ? 'text-right' : 'text-left'">
                <div class="inline-block rounded-2xl px-3 py-2 text-sm"
                     :class="m.sender_id === me ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-800'">
                  <span x-text="m.message"></span>
                </div>
                <div class="text-[11px] text-gray-400 mt-0.5" x-text="formatTime(m.created_at)"></div>
              </div>
            </template>
          </div>
        </template>
      @else
        <div class="text-sm text-gray-600">
          Silakan <a href="{{ route('login') }}" class="text-blue-600 underline">login</a> untuk menggunakan chat.
        </div>
      @endauth
    </div>

    {{-- Input (hanya chats mode) --}}
    @auth
    <form x-show="mode==='chats'" x-on:submit.prevent="send()" class="border-t bg-white">
      <div class="flex items-center gap-2 px-3 py-2">
        <input
          x-model="draft"
          type="text"
          placeholder="Tulis pesan..."
          class="flex-1 border rounded-full px-3 py-2 text-sm focus:outline-none focus:ring ring-blue-200"
          maxlength="2000"
        />
        <button
          type="submit"
          :disabled="sending || draft.trim()===''"
          class="px-3 py-2 rounded-full bg-blue-600 text-white text-sm hover:bg-blue-700 disabled:opacity-50">
          Kirim
        </button>
      </div>
    </form>
    @endauth

    {{-- DEBUG PANEL --}}
    <div x-show="showDebug" class="border-t bg-gray-50">
      <div class="px-3 py-2 flex items-center gap-2 text-xs text-gray-600">
        <span class="font-semibold">DEBUG</span>
        <span>mode: <b x-text="mode"></b></span>
        <span>roomId: <b x-text="roomId"></b></span>
        <span>me: <b x-text="me"></b></span>
        <button @click="logs=[]" class="ml-auto px-2 py-1 border rounded">Clear</button>
        <button @click="copyLogs" class="px-2 py-1 border rounded">Copy</button>
      </div>
      <div class="h-28 overflow-y-auto px-3 pb-3 text-[11px] font-mono bg-white border-t">
        <template x-for="(line, i) in logs" :key="i">
          <div x-text="line"></div>
        </template>
      </div>
    </div>
  </div>
</div>

<script>
/** Chat widget Alpine store + debug log */
function chatWidget(config) {
  return {
    // UI state
    open: false,
    mode: 'chats',             // 'chats' | 'users'
    roomTitle: 'Obrolan Yayasan',
    unread: 0,
    showDebug: false,

    // chat state
    roomId: config?.roomId ?? 1,
    messages: Array.isArray(config?.initial) ? config.initial : [],
    draft: '',
    sending: false,
    me: config?.me ?? null,

    // users search state
    userQuery: '',
    users: [],
    usersLoading: false,
    suggestOpen: false,
    selIndex: -1,
    minChars: 1,
    _searchTimer: null,
    _channel: null,

    // debug
    logs: [],
    log(msg) {
      const line = `[${new Date().toLocaleTimeString()}] ${msg}`;
      this.logs.push(line);
      if (this.logs.length > 200) this.logs.shift();
      try { console.debug('[chat-widget]', msg); } catch {}
    },
    copyLogs() {
      navigator.clipboard.writeText(this.logs.join('\n')).catch(()=>{});
    },

    init() {
      this.log('init() start');
      this.log(`bootstrap roomId=${this.roomId}, me=${this.me}`);
      this._subscribe(this.roomId);
      window.addEventListener('chat:switch', (e) => {
        const newRoomId = e.detail?.roomId;
        this.log(`event chat:switch roomId=${newRoomId}`);
        if (!newRoomId || newRoomId === this.roomId) return;
        this.switchRoom(newRoomId);
      });
      this.log('init() done');
    },

    // ---------- Tabs ----------
    switchMode(m) {
      this.log(`switchMode(${m})`);
      this.mode = m;
      if (m === 'users') {
        this.suggestOpen = true;
        this.selIndex = -1;
        // load default list meski query kosong
        this.searchUsers(this.userQuery.trim());
      } else {
        this.suggestOpen = false;
      }
    },

    // ---------- Users search ----------
    onSearchFocus() {
      if (this.mode !== 'users') return;
      this.log('onSearchFocus()');
      this.suggestOpen = true;
      this.searchUsers(this.userQuery.trim());
    },
    onSearchInput() {
      clearTimeout(this._searchTimer);
      const q = (this.userQuery || '').trim();
      this._searchTimer = setTimeout(() => {
        this.log(`onSearchInput: q="${q}"`);
        if (q.length === 0 || q.length >= this.minChars) {
          this.searchUsers(q);
          this.suggestOpen = true;
          this.selIndex = -1;
        } else {
          this.users = [];
          this.suggestOpen = false;
        }
      }, 250);
    },
    async searchUsers(q) {
      this.usersLoading = true;
      this.log(`searchUsers("${q}") → GET {{ route('chat.users') }}?q=${encodeURIComponent(q)}&take=10`);
      try {
        const r = await fetch(`{{ route('chat.users') }}?q=${encodeURIComponent(q)}&take=10`, {
          headers: { 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' }
        });
        this.log(`searchUsers status=${r.status}`);
        if (!r.ok) {
          this.log(`searchUsers failed status=${r.status}`);
          this.users = [];
          return;
        }
        const d = await r.json().catch(()=>({ok:false, parseError:true}));
        this.log(`searchUsers json=${JSON.stringify(d).slice(0,200)}...`);
        this.users = Array.isArray(d?.users) ? d.users : [];
        this.selIndex = this.users.length ? 0 : -1;
      } catch (e) {
        this.log(`searchUsers error=${e?.message || e}`);
        this.users = [];
        this.selIndex = -1;
      } finally {
        this.usersLoading = false;
      }
    },
    moveSel(dir) {
      if (!this.suggestOpen || !this.users.length) return;
      const max = this.users.length - 1;
      let next = this.selIndex + dir;
      if (next < 0) next = max;
      if (next > max) next = 0;
      this.selIndex = next;
    },
    selectSel() {
      if (!this.suggestOpen) return;
      const u = this.users[this.selIndex];
      if (u) this.selectUser(u);
    },
    async selectUser(u) {
      this.log(`selectUser id=${u.id} name="${u.name}" → POST /chat/dm/${u.id}`);
      try {
        const r = await fetch(`{{ url('/chat/dm') }}/${u.id}`, {
          method: 'POST',
          headers: {
            'Accept':'application/json',
            'X-Requested-With':'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
          }
        });
        this.log(`dm status=${r.status}`);
        const d = await r.json().catch(()=>({ok:false, parseError:true}));
        this.log(`dm json=${JSON.stringify(d)}`);
        if (!d?.ok) { alert('DM gagal: ' + (d?.error || 'unknown')); return; }
        await this.switchRoom(d.room_id, `${u.name}`);
        this.mode = 'chats';
        this.open = true;
        this.suggestOpen = false;
        this.$nextTick(() => this.scrollToBottom());
      } catch (e) {
        this.log(`dm error=${e?.message || e}`);
        alert('DM request error.');
      }
    },

    // ---------- Room switch & history ----------
    async switchRoom(newRoomId, title = 'Percakapan') {
      this.log(`switchRoom(${newRoomId})`);
      this._unsubscribe(this.roomId);
      this.roomId = newRoomId;
      this.roomTitle = title || 'Percakapan';
      try {
        const url = `{{ url('/chat/room') }}/${newRoomId}/messages?take=50`;
        this.log(`fetch messages → GET ${url}`);
        const r = await fetch(url, {
          headers: { 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' }
        });
        this.log(`messages status=${r.status}`);
        const d = await r.json().catch(()=>({ok:false, parseError:true}));
        this.log(`messages json=${JSON.stringify(d).slice(0,200)}...`);
        this.messages = Array.isArray(d?.messages) ? d.messages : [];
      } catch (e) {
        this.log(`messages error=${e?.message || e}`);
        this.messages = [];
      }
      this._subscribe(this.roomId);
    },

    // ---------- Send ----------
    async send() {
      if (!this.me) { alert('Silakan login.'); return; }
      const text = (this.draft || '').trim();
      if (!text || this.sending) return;

      this.sending = true;
      try {
        const url = `{{ url('/chat/room') }}/${this.roomId}/send`;
        this.log(`send → POST ${url} body="${text}"`);
        const res = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type':'application/json',
            'Accept':'application/json',
            'X-Requested-With':'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
          },
          body: JSON.stringify({ message: text })
        });
        this.log(`send status=${res.status}`);
        if (!res.ok) {
          const t = await res.text();
          this.log(`send failed payload=${t.slice(0,200)}...`);
        } else {
          const now = new Date().toISOString();
          this.messages.push({ id: 'local-'+Date.now(), sender_id: this.me, message: text, created_at: now });
          this.$nextTick(() => this.scrollToBottom());
        }
        this.draft = '';
      } catch (e) {
        this.log(`send error=${e?.message || e}`);
      } finally {
        this.sending = false;
      }
    },

    // ---------- Echo ----------
    _subscribe(roomId) {
      if (!window?.Echo) { this.log('Echo not available'); return; }
      try {
        const ch = 'room.' + roomId;
        this.log(`Echo subscribe ${ch}`);
        this._channel = window.Echo.channel(ch)
          .listen('.message.sent', (e) => {
            this.log(`Echo event: ${JSON.stringify(e).slice(0,200)}...`);
            this.messages.push({
              id: e.id, sender_id: e.sender_id, message: e.message, created_at: e.created_at
            });
            if (!this.open) this.unread++;
            this.$nextTick(() => this.scrollToBottom());
          });
      } catch (err) {
        this.log('Echo subscribe error=' + (err?.message || err));
      }
    },
    _unsubscribe(roomId) {
      try {
        const ch = 'room.' + roomId;
        this.log(`Echo leave ${ch}`);
        if (this._channel) { window.Echo.leave(ch); }
      } catch (e) {
        this.log('Echo leave error=' + (e?.message || e));
      }
    },

    // ---------- Utils ----------
    scrollToBottom() {
      const box = document.getElementById('chat-scroll-box');
      if (box) box.scrollTop = box.scrollHeight;
      this.unread = 0;
    },
    formatTime(iso) {
      try { return new Date(iso).toLocaleString(); } catch { return iso; }
    }
  }
}
</script>
