@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto">
  <h2 class="text-xl font-semibold mb-3">Room #{{ $room->id }}</h2>
  <div id="messages" class="border rounded p-3 h-96 overflow-y-auto">
    @foreach($messages as $m)
      <div class="mb-2">
        <b>User {{ $m->sender_id }}:</b> {{ $m->message }}
        <div class="text-xs text-gray-500">{{ $m->created_at }}</div>
      </div>
    @endforeach
  </div>

  <form method="POST" action="{{ route('chat.send',$room) }}" class="mt-3 flex gap-2">
    @csrf
    <input name="message" class="border rounded px-3 py-2 flex-1" placeholder="Tulis pesan..." />
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Kirim</button>
  </form>
</div>
@endsection

@push('scripts')
<script src="/js/app.js"></script>
<script>
  // Echo sudah di-boostrap oleh vite (resources/js/bootstrap.js)
  window.Echo.channel('room.{{ $room->id }}')
    .listen('.message.sent', (e) => {
      const box = document.getElementById('messages');
      const el = document.createElement('div');
      el.className='mb-2';
      el.innerHTML = `<b>User ${e.sender_id}:</b> ${e.message}
        <div class="text-xs text-gray-500">${e.created_at}</div>`;
      box.appendChild(el);
      box.scrollTop = box.scrollHeight;
    });
</script>
@endpush
