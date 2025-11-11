@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">
  <div>
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Buat Campaign</h1>
    <p class="text-gray-600">Lengkapi detail campaign donasi yayasan.</p>
  </div>

  @if ($errors->any())
    <div class="mt-4 rounded-lg bg-red-50 text-red-800 px-4 py-3 text-sm ring-1 ring-red-200">
      @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
  @endif

  <form method="post" action="{{ route('admin.campaigns.store') }}" class="mt-6 space-y-5">
    @csrf
    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="text-sm font-medium text-gray-700">Judul</label>
        <input name="title" value="{{ old('title') }}"
               class="mt-1 w-full border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50" required>
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Slug (opsional)</label>
        <input name="slug" value="{{ old('slug') }}"
               class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
      </div>
    </div>

    <div>
      <label class="text-sm font-medium text-gray-700">Ringkasan (maks 300)</label>
      <input name="excerpt" value="{{ old('excerpt') }}"
             class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
    </div>

    <div>
      <label class="text-sm font-medium text-gray-700">Deskripsi</label>
      <textarea name="description" rows="6"
                class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">{{ old('description') }}</textarea>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
      <div>
        <label class="text-sm font-medium text-gray-700">Target (Rp)</label>
        <input type="number" name="goal_amount" min="0" value="{{ old('goal_amount') }}"
               class="mt-1 w-full border rounded-xl px-3 py-2 text-sm" required>
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Deadline</label>
        <input type="datetime-local" name="deadline_at" value="{{ old('deadline_at') }}"
               class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Status</label>
        <select name="status" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
          @foreach (['draft','published','closed'] as $s)
            <option value="{{ $s }}" @selected(old('status')===$s)>{{ $s }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div>
      <label class="text-sm font-medium text-gray-700">Cover (path/url opsional)</label>
      <input name="cover_path" value="{{ old('cover_path') }}"
             class="mt-1 w-full border rounded-xl px-3 py-2 text-sm" placeholder="mis. uploads/covers/abc.jpg">
      <p class="text-xs text-gray-500 mt-1">Nanti bisa ditingkatkan jadi upload file.</p>
    </div>

    <div class="flex items-center gap-3">
      <a href="{{ route('admin.campaigns.index') }}" class="px-4 py-2 rounded-xl border text-gray-700">Batal</a>
      <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Simpan</button>
    </div>
  </form>
</div>
@endsection
