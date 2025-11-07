{{-- resources/views/components/chat-widget.blade.php --}}
@php
    use App\Models\ChatMessage;

    // Bootstrap room (opsional, bisa kamu ubah dari config/chat.php)
    $roomId = (int) (config('chat.default_room_id') ?? 1);
    $take   = (int) (config('chat.initial_take') ?? 30);

    $initialMessages = auth()->check()
        ? ChatMessage::where('room_id', $roomId)->latest()->limit($take)->get()->reverse()
        : collect();

    $initialPayload = $initialMessages->map(fn($m)=>[
        'id'         => (int)$m->id,
        'sender_id'  => (int)$m->sender_id,
        'message'    => (string)$m->message,
        'created_at' => optional($m->created_at)->toIso8601String(),
    ])->values()->all();

    $chatConfig = [
        'bootstrapRoom' => ['id'=>$roomId, 'title'=>'Obrolan Yayasan', 'initial'=>$initialPayload],
        'me' => auth()->id(),
    ];
@endphp

<div id="chat-widget-root" x-data='chatWidget(@json($chatConfig))' x-init="init()" class="fixed z-[9999] bottom-4 right-4">

  {{-- FAB --}}
  <button
    @click="open = !open; if(open){ $nextTick(()=>scrollToBottom()) }"
    class="relative rounded-full shadow-lg bg-blue-600 text-white w-14 h-14 flex items-center justify-center hover:bg-blue-700"
    aria-label="Open chat" title="Chat"
  >
    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
      <path d="M20 2H4a2 2 0 00-2 2v18l4-4h14a2 2 0 002-2V4a2 2 0 00-2-2z"/>
    </svg>
    <template x-if="totalUnread()>0">
      <span class="absolute -top-1 -right-1 text-xs bg-red-600 text-white rounded-full px-1.5 py-0.5" x-text="totalUnread()"></span>
    </template>
  </button>

  {{-- PANEL --}}
  <div x-show="open" x-transition class="mt-3 w-[22rem] sm:w-[26rem] rounded-2xl shadow-xl border bg-white overflow-hidden">

    {{-- HEADER --}}
    <div class="px-4 pt-3 bg-gray-50 border-b">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="text-xs text-gray-500">Chat Yayasan</div>
          <span class="px-1.5 py-0.5 rounded bg-gray-200 text-[10px]" x-text="badges.js"></span>
          <span class="px-1.5 py-0.5 rounded bg-gray-200 text-[10px]" x-text="badges.alpine"></span>
          <span class="px-1.5 py-0.5 rounded bg-gray-200 text-[10px]" x-text="badges.echo"></span>
        </div>
        <div class="flex items-center gap-2">
          <button @click="showDebug=!showDebug" class="text-[11px] px-2 py-1 border rounded hover:bg-gray-100" :class="showDebug ? 'border-blue-500 text-blue-600' : 'border-gray-300 text-gray-600'">Debug</button>
          <button @click="open=false" class="text-gray-500 hover:text-gray-700" aria-label="Close">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
          </button>
        </div>
      </div>

      {{-- MAIN TABS: Chats / Users --}}
      <div class="mt-2 grid grid-cols-2 gap-1">
        <button @click="switchMain('chats')" :class="main==='chats' ? 'bg-white' : 'bg-gray-100'" class="py-1.5 rounded-lg text-sm border">Chats</button>
        <button @click="switchMain('users')" :class="main==='users' ? 'bg-white' : 'bg-gray-100'" class="py-1.5 rounded-lg text-sm border">Users</button>
      </div>

      {{-- SEARCH (Users) --}}
      <div class="mt-2 relative" x-show="main==='users'">
        <input id="chat-user-search"
          x-model="userQuery"
          @input="onSearchInput"
          @focus="onSearchFocus"
          @keydown.arrow-down.prevent="moveSel(1)"
          @keydown.arrow-up.prevent="moveSel(-1)"
          @keydown.enter.prevent="selectSel()"
          type="text" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Cari nama/email…"
          autocomplete="off">
        <div x-show="suggestOpen" x-transition class="absolute z-50 mt-1 w-full bg-white border rounded-lg shadow">
          <template x-if="usersLoading"><div class="px-3 py-2 text-sm text-gray-500">Mencari…</div></template>
          <template x-if="!usersLoading && users.length===0"><div class="px-3 py-2 text-sm text-gray-500">Tidak ada hasil.</div></template>
          <template x-for="(u, idx) in users" :key="u.id">
            <button type="button" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50" :class="idx===selIndex ? 'bg-blue-50' : ''"
              @mouseenter="selIndex=idx" @mouseleave="selIndex=-1" @click="openChatWithUser(u)">
              <div class="font-medium" x-text="u.name"></div>
              <div class="text-xs text-gray-500" x-text="u.email"></div>
            </button>
          </template>
        </div>
      </div>

      {{-- CHAT TABS STRIP (WhatsApp-like) --}}
      <div class="mt-2 -mb-1 overflow-x-auto">
        <div class="flex gap-1 pb-1" style="scrollbar-width: thin;">
          <template x-for="(c, rid) in openChats" :key="rid">
            <div class="flex items-center gap-1 px-2 py-1 rounded-lg border"
                 :class="(+rid===activeRoom && !c.minimized) ? 'bg-white' : 'bg-gray-100'">
              <button class="text-sm font-medium max-w-[8rem] truncate"
                      @click="toggleMin(+rid)"
                      :title="c.title + (c.minimized ? ' (minimized)' : '')"
                      x-text="c.title"></button>
              <template x-if="c.unread>0">
                <span class="text-[10px] bg-red-600 text-white rounded-full px-1.5" x-text="c.unread"></span>
              </template>
              <button @click="activate(+rid)" class="text-gray-500 hover:text-gray-700" title="Open">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M3 4a2 2 0 012-2h7a1 1 0 110 2H5v10h10v-7a1 1 0 112 0v7a2 2 0 01-2 2H5a2 2 0 01-2-2V4z"/></svg>
              </button>
              <button @click="closeChat(+rid)" class="text-gray-500 hover:text-red-600" title="Close">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
              </button>
            </div>
          </template>
        </div>
      </div>
    </div>

    {{-- BODY --}}
    <div class="h-[26rem] overflow-y-auto px-4 py-3 bg-white" id="chat-scroll-box">
      @auth
        {{-- CHATS PAGE --}}
        <template x-if="main==='chats'">
          <div>
            {{-- RECENT (muncul jika belum ada chat aktif) --}}
            <template x-if="!current()">
              <div class="mb-3">
                <div class="text-xs text-gray-500 mb-2">Recent</div>

                <template x-if="recentLoading">
                  <div class="text-sm text-gray-500">Memuat…</div>
                </template>

                <template x-if="!recentLoading && recent.length===0">
                  <div class="text-sm text-gray-500">Belum ada percakapan. Cari user di tab <b>Users</b> untuk mulai DM.</div>
                </template>

                <template x-for="item in recent" :key="item.room_id">
                  <button
                    class="w-full text-left py-2 border-b last:border-b-0 hover:bg-gray-50"
                    @click="ensureChatLoaded(item.room_id, item.title).then(()=>{ main='chats'; activate(item.room_id); open=true; })"
                  >
                    <div class="flex items-start justify-between">
                      <div class="pr-2">
                        <div class="font-medium truncate" x-text="item.title"></div>
                        <div class="text-xs text-gray-500 truncate" x-text="item.last_message || '-'"></div>
                      </div>
                      <div class="text-right">
                        <div class="text-[10px] text-gray-400" x-text="item.last_at ? formatTime(item.last_at) : ''"></div>
                        <template x-if="item.unread > 0">
                          <div class="mt-1 text-[10px] bg-red-600 text-white rounded-full px-1.5 text-center" x-text="item.unread"></div>
                        </template>
                      </div>
                    </div>
                  </button>
                </template>
              </div>
            </template>

            {{-- CHAT KONTEN --}}
            <template x-if="current() && current().minimized">
              <div class="text-sm text-gray-500">Chat <span class="font-medium" x-text="current().title"></span> sedang diminimize. Klik tombol “Open” pada tab untuk membuka.</div>
            </template>

            <template x-if="current() && !current().minimized">
              <div>
                <div class="text-xs text-gray-500 mb-2">Chat dengan <span class="font-semibold" x-text="current().title"></span></div>

                <template x-if="current().messages.length===0">
                  <div class="text-sm text-gray-500">Belum ada pesan.</div>
                </template>

                <template x-for="m in current().messages" :key="m.id">
                  <div class="mb-3" :class="m.sender_id===me ? 'text-right' : 'text-left'">
                    <div class="inline-block rounded-2xl px-3 py-2 text-sm"
                         :class="m.sender_id===me ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-800'">
                      <span x-text="m.message"></span>
                    </div>
                    <div class="text-[11px] text-gray-400 mt-0.5" x-text="formatTime(m.created_at)"></div>
                  </div>
                </template>
              </div>
            </template>
          </div>
        </template>

        {{-- USERS PAGE LIST (ketika dropdown tertutup) --}}
        <template x-if="main==='users'">
          <div class="pt-1">
            <template x-if="!suggestOpen && users.length===0 && !usersLoading">
              <div class="text-sm text-gray-500">Ketik untuk mencari user…</div>
            </template>
            <template x-if="!suggestOpen && users.length>0">
              <div>
                <div class="text-xs text-gray-500 mb-1">Hasil</div>
                <template x-for="u in users" :key="u.id">
                  <button @click="openChatWithUser(u)" class="w-full text-left py-2 border-b last:border-b-0 hover:bg-gray-50">
                    <div class="font-medium" x-text="u.name"></div>
                    <div class="text-xs text-gray-500" x-text="u.email"></div>
                  </button>
                </template>
              </div>
            </template>
          </div>
        </template>
      @else
        <div class="text-sm text-gray-600">Silakan <a href="{{ route('login') }}" class="text-blue-600 underline">login</a> untuk menggunakan chat.</div>
      @endauth
    </div>

    {{-- INPUT --}}
    @auth
    <form x-show="main==='chats' && current() && !current().minimized" x-on:submit.prevent="send()" class="border-t bg-white">
      <div class="flex items-center gap-2 px-3 py-2">
        <input x-model="draft" type="text" placeholder="Tulis pesan…" class="flex-1 border rounded-full px-3 py-2 text-sm focus:outline-none focus:ring ring-blue-200" maxlength="2000"/>
        <button type="submit" :disabled="sending || draft.trim()===''" class="px-3 py-2 rounded-full bg-blue-600 text-white text-sm hover:bg-blue-700 disabled:opacity-50">Kirim</button>
      </div>
    </form>
    @endauth

    {{-- DEBUG --}}
    <div x-show="showDebug" class="border-t bg-gray-50">
      <div class="px-3 py-2 flex flex-wrap items-center gap-2 text-xs text-gray-600">
        <span class="font-semibold">DEBUG</span>
        <span>main: <b x-text="main"></b></span>
        <span>activeRoom: <b x-text="activeRoom"></b></span>
        <span>open: <b x-text="Object.keys(openChats).length"></b></span>
        <span>unread: <b x-text="totalUnread()"></b></span>
        <button @click="logs=[]" class="ml-auto px-2 py-1 border rounded">Clear</button>
        <button @click="copyLogs" class="px-2 py-1 border rounded">Copy</button>
      </div>
      <div class="h-28 overflow-y-auto px-3 pb-3 text-[11px] font-mono bg-white border-t">
        <template x-for="(line,i) in logs" :key="i"><div x-text="line"></div></template>
      </div>
    </div>
  </div>
</div>

<script>
function chatWidget(cfg){
  return {
    // badges
    badges:{ js:'JS OK', alpine:'Alpine OK', echo: (window.Echo?'Echo OK':'Echo -') },

    // panel
    open:false, showDebug:false, main:'chats',

    // me
    me: cfg?.me ?? null,

    // multi-thread store
    openChats: {},        // { [roomId]: { title, messages[], minimized, unread, channel } }
    activeRoom: null,

    // compose
    draft:'', sending:false,

    // users search
    userQuery:'', users:[], usersLoading:false,
    suggestOpen:false, selIndex:-1, minChars:1, _searchTimer:null,

    // recent contacts
    recent: [], recentLoading: false,

    // logs
    logs:[],
    log(m){ const s='['+new Date().toLocaleTimeString()+'] '+m; this.logs.push(s); if(this.logs.length>300)this.logs.shift(); try{console.log('[chat]',m);}catch{} },
    copyLogs(){ navigator.clipboard.writeText(this.logs.join('\n')).catch(()=>{}); },

    // lifecycle
    init(){
      // bootstrap default room as opened chat
      const b = cfg?.bootstrapRoom;
      if (b?.id) {
        this.openChats[b.id] = { title: b.title || 'Percakapan', messages: Array.isArray(b.initial)? b.initial: [], minimized:false, unread:0, channel:null };
        this.activeRoom = b.id;
        this._subscribe(b.id);
        this.log('BOOT room='+b.id);
      }
      // restore tabs from localStorage
      this._restoreTabs();
      // support external room switch
      window.addEventListener('chat:switch',(e)=>{ const id=e.detail?.roomId; if(id) this.activate(+id); });
      // fetch recent list
      this.fetchRecent();
    },

    // computed helpers
    current(){ return this.activeRoom && this.openChats[this.activeRoom] ? this.openChats[this.activeRoom] : null; },
    totalUnread(){ return Object.values(this.openChats).reduce((a,c)=>a+(c.unread||0),0); },

    // state switches
    switchMain(m){ this.main=m; if(m==='users'){ this.suggestOpen=true; this._searchNow(); } },
    activate(roomId){
      if(!this.openChats[roomId]){ this.log('ACTIVATE missing room '+roomId); return; }
      this.activeRoom = roomId;
      const c = this.openChats[roomId];
      c.minimized = false;
      c.unread = 0;
      this._persistTabs();
      this.$nextTick(()=>this.scrollToBottom());
      this.fetchRecent(); // refresh recent/unread setelah aktifkan
    },
    toggleMin(roomId){
      const c = this.openChats[roomId]; if(!c) return;
      c.minimized = !c.minimized;
      if (!c.minimized) { this.activeRoom = roomId; c.unread = 0; this.$nextTick(()=>this.scrollToBottom()); }
      this._persistTabs();
      this.fetchRecent();
    },
    closeChat(roomId){
      const wasActive = (this.activeRoom === roomId);
      this._unsubscribe(roomId);
      delete this.openChats[roomId];
      if (wasActive) {
        const keys = Object.keys(this.openChats);
        this.activeRoom = keys.length ? +keys[keys.length-1] : null;
      }
      this._persistTabs();
      this.fetchRecent();
    },

    // open chat by user (create/find DM first)
    async openChatWithUser(u){
      try{
        const url = `{{ url('/chat/dm') }}/${u.id}`;
        this.log('DM '+url);
        const r = await fetch(url,{method:'POST',headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}});
        const d = await r.json().catch(()=>({ok:false}));
        this.log('DM resp '+JSON.stringify(d));
        if(!d?.ok){ alert('DM gagal: '+(d?.error||'unknown')); return; }
        await this.ensureChatLoaded(d.room_id, u.name);
        this.main = 'chats';
        this.activate(d.room_id);
        this.open = true;
        this.fetchRecent();
      }catch(e){ this.log('DM error '+(e?.message||e)); alert('DM request error.'); }
    },

    // ensure chat exists locally and messages are loaded
    async ensureChatLoaded(roomId, title='Percakapan'){
      if(!this.openChats[roomId]){
        this.openChats[roomId] = { title, messages:[], minimized:false, unread:0, channel:null };
        this._persistTabs();
      }
      // load history only if empty
      if(this.openChats[roomId].messages.length === 0){
        try{
          const url = `{{ url('/chat/room') }}/${roomId}/messages?take=50`;
          this.log('HISTORY '+url);
          const r = await fetch(url,{headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}});
          const d = await r.json().catch(()=>({ok:false}));
          const arr = Array.isArray(d?.messages) ? d.messages : [];
          this.openChats[roomId].messages = arr;
        }catch(e){ this.log('HISTORY error '+(e?.message||e)); }
      }
      // subscribe (once)
      this._subscribe(roomId);
    },

    // send message to active chat
    async send(){
      const c = this.current(); if(!c){ alert('Tidak ada chat aktif.'); return; }
      if(!this.me){ alert('Silakan login.'); return; }
      const text = (this.draft||'').trim(); if(!text || this.sending) return;

      this.sending = true;
      try{
        const url = `{{ url('/chat/room') }}/${this.activeRoom}/send`;
        this.log('SEND '+url+' "'+text+'"');
        const r = await fetch(url,{
          method:'POST',
          headers:{
            'Content-Type':'application/json',
            'Accept':'application/json',
            'X-Requested-With':'XMLHttpRequest',
            'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content
          },
          body: JSON.stringify({message:text})
        });
        if(r.ok){
          const now = new Date().toISOString();
          c.messages.push({ id:'local-'+Date.now(), sender_id:this.me, message:text, created_at:now });
          this.$nextTick(()=>this.scrollToBottom());
          this.fetchRecent();
        }else{
          this.log('SEND fail '+(await r.text()).slice(0,200)+'…');
        }
        this.draft='';
      }catch(e){ this.log('SEND error '+(e?.message||e)); }
      finally{ this.sending=false; }
    },

    // realtime
    _subscribe(roomId){
      if(!window?.Echo){ this.badges.echo='Echo -'; return; }
      const c = this.openChats[roomId]; if(!c) return;
      if (c.channel) return; // already subscribed
      const ch = 'room.'+roomId;
      this.badges.echo='Echo OK';
      try{
        c.channel = window.Echo.channel(ch).listen('.message.sent', (e)=>{
          const msg = { id:e.id, sender_id:e.sender_id, message:e.message, created_at:e.created_at };
          // ensure store exists
          if(!this.openChats[roomId]) this.openChats[roomId] = { title:'Percakapan', messages:[], minimized:false, unread:0, channel:c.channel };
          this.openChats[roomId].messages.push(msg);
          // unread logic
          if (this.activeRoom !== roomId || this.openChats[roomId].minimized || !this.open) {
            this.openChats[roomId].unread = (this.openChats[roomId].unread||0) + 1;
          }
          this.$nextTick(()=>{ if(this.activeRoom===roomId && !this.openChats[roomId].minimized) this.scrollToBottom(); });
          this.fetchRecent(); // sinkron preview & counter
        });
      }catch(e){ this.log('ECHO sub err '+(e?.message||e)); }
    },
    _unsubscribe(roomId){
      try{ if(window?.Echo){ window.Echo.leave('room.'+roomId); } }catch{}
      const c = this.openChats[roomId]; if(c) c.channel=null;
    },

    // users search helpers
    onSearchFocus(){ if(this.main!=='users')return; this.suggestOpen=true; this._searchNow(); },
    onSearchInput(){
      clearTimeout(this._searchTimer);
      const q = (this.userQuery||'').trim();
      this._searchTimer = setTimeout(()=> this._searchNow(q), 220);
    },
    async _searchNow(q = (this.userQuery||'').trim()){
      this.usersLoading=true;
      try{
        const url = `{{ route('chat.users') }}?q=${encodeURIComponent(q)}&take=10`;
        this.log('SEARCH '+url);
        const r = await fetch(url,{headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}});
        const d = await r.json().catch(()=>({ok:false}));
        this.users = Array.isArray(d?.users) ? d.users : [];
        this.selIndex = this.users.length ? 0 : -1;
      }catch(e){ this.log('SEARCH error '+(e?.message||e)); this.users=[]; this.selIndex=-1; }
      finally{ this.usersLoading=false; }
    },
    moveSel(d){ if(!this.suggestOpen||!this.users.length) return; const max=this.users.length-1; let n=this.selIndex+d; if(n<0)n=max; if(n>max)n=0; this.selIndex=n; },
    selectSel(){ if(!this.suggestOpen) return; const u=this.users[this.selIndex]; if(u) this.openChatWithUser(u); },

    // recent
    async fetchRecent() {
      this.recentLoading = true;
      try {
        const url = `{{ route('chat.recent') }}?take=20`;
        this.log('RECENT ' + url);
        const r = await fetch(url, { headers: { 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' } });
        const d = await r.json().catch(()=>({ok:false}));
        this.recent = Array.isArray(d?.items) ? d.items : [];
      } catch (e) {
        this.log('RECENT error ' + (e?.message||e));
        this.recent = [];
      } finally { this.recentLoading = false; }
    },

    // utils
    scrollToBottom(){ const box=document.getElementById('chat-scroll-box'); if(box) box.scrollTop=box.scrollHeight; },
    formatTime(iso){ try{ return new Date(iso).toLocaleString(); }catch{ return iso; } },

    // persistence (remember opened tabs & titles)
    _persistTabs(){
      try{
        const data = Object.entries(this.openChats).map(([rid,c])=>({ id:+rid, title:c.title, minimized: !!c.minimized }));
        localStorage.setItem('chat_tabs', JSON.stringify({ active:this.activeRoom, tabs:data }));
      }catch{}
    },
    async _restoreTabs(){
      try{
        const raw = localStorage.getItem('chat_tabs'); if(!raw) return;
        const {active, tabs} = JSON.parse(raw||'{}') || {};
        if(Array.isArray(tabs)){
          for(const t of tabs){
            if(!this.openChats[t.id]){
              this.openChats[t.id] = { title: t.title || 'Percakapan', messages:[], minimized: !!t.minimized, unread:0, channel:null };
              // load history lazily
              this.ensureChatLoaded(t.id, t.title || 'Percakapan');
            }
          }
        }
        if(active && this.openChats[active]) this.activeRoom = active;
        this.log('RESTORE tabs='+Object.keys(this.openChats).length+' active='+this.activeRoom);
      }catch{}
    }
  }
}
</script>
