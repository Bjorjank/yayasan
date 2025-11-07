@extends('layouts.app')

@section('content')
@php
  $me = auth()->id();
  // cari satu user lain (kalau ada)
  $peerId = \App\Models\User::where('id','!=',$me)->value('id');
@endphp

<div class="max-w-xl mx-auto p-6 space-y-4">
  <h1 class="text-xl font-semibold">DM Test Page</h1>

  <p>Login sebagai: <b>{{ auth()->user()->name }}</b> (ID: {{ $me }})</p>

  @if(!$peerId)
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-3 rounded">
      Belum ada user lain untuk DM. Buat 1 akun lagi (register) atau jalankan seeder
      <code>ChatTestSeeder</code> untuk membuat <b>Alice</b> & <b>Bob</b>.
    </div>
  @else
    <p>Target DM: User ID <b>{{ $peerId }}</b></p>

    <button
      type="button"
      id="btnDmPeer"
      class="px-3 py-2 bg-gray-800 text-white rounded"
    >DM dengan User #{{ $peerId }}</button>

    <pre id="log" class="bg-gray-100 p-2 rounded text-sm overflow-x-auto"></pre>

    <script>
    (function(){
      const btn = document.getElementById('btnDmPeer');
      const log = document.getElementById('log');
      function put(x){ log.textContent = (log.textContent + x + '\n'); }
      btn.addEventListener('click', async (ev)=>{
        ev.preventDefault(); ev.stopPropagation();
        try{
          const r = await fetch("{{ route('chat.dm', $peerId) }}", {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            }
          });
          put('STATUS ' + r.status);
          const d = await r.json().catch(()=>({ok:false, parseError:true}));
          put('JSON ' + JSON.stringify(d));
          if(!d.ok){ alert('DM gagal: ' + (d.error || 'unknown')); return; }
          window.dispatchEvent(new CustomEvent('chat:switch', { detail: { roomId: d.room_id } }));
          alert('DM OK. Room ID: ' + d.room_id);
        }catch(e){ console.error(e); put('ERR ' + e.message); }
      });
    })();
    </script>
  @endif
</div>
@endsection
