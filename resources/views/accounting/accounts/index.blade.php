@extends('layout.main')
@section('title', 'Daftar Akun')

@section('content')
<div x-data class="p-6 bg-gray-50 min-h-screen">

  <div class="bg-white shadow-md rounded-2xl p-6">
    <!-- Header Card -->
    <div class="w-full mb-6">
      <div class="bg-gradient-to-tl from-purple-700 to-pink-500 rounded-2xl shadow-lg flex flex-col items-center px-6 py-6">
        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/20 mb-2">
          <i class="fa-solid fa-list text-white text-2xl"></i>
        </div>
        <h2 class="text-2xl font-extrabold text-white tracking-tight mb-1 drop-shadow text-center">Daftar Akun</h2>
      </div>
      <div class="mt-4 flex justify-center">
        <button @click="$store.modal.openAdd = true"
          type="button"
          class="inline-block px-6 py-3 font-bold text-center uppercase align-middle transition-all bg-transparent border rounded-lg cursor-pointer border-fuchsia-500 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-fuchsia-500">
          Tambah Akun
        </button>
      </div>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
      <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
        {{ session('success') }}
      </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="p-3 text-left">Kode Akun</th>
            <th class="p-3 text-left">Nama Akun</th>
            <th class="p-3 text-left">Jenis Akun</th>
            <th class="p-3 text-left">Saldo Normal</th>
            <th class="p-3 text-left">Saldo Awal</th>
            <th class="p-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($accounts as $account)
            <tr class="border-t hover:bg-gray-50 transition">
              <td class="p-3">{{ $account->kode_akun }}</td>
              <td class="p-3">{{ $account->nama_akun }}</td>
              <td class="p-3 capitalize">{{ $account->jenis_akun }}</td>
              <td class="p-3 capitalize">{{ $account->saldo_normal }}</td>
              <td class="p-3">Rp {{ number_format($account->saldo_awal, 2, ',', '.') }}</td>
              <td class="p-3 text-center">
                <button @click="$store.modal.openEdit = '{{ $account->id }}'"
                  type="button"
                  class="mr-3 inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-red-500 to-yellow-400 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                  <i class="fa fa-pen"></i>
                </button>

                <form action="{{ route('accounts.destroy', $account->id) }}" method="POST" class="inline">
                  @csrf @method('DELETE')
                  <button onclick="return confirm('Yakin hapus akun ini?')"
                    type="submit"
                    class="mr-3 inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-red-600 to-rose-400 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                    <i class="fa fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4 text-gray-500">Belum ada data akun.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Modal Tambah Akun --}}
  <div 
    x-show="$store.modal.openAdd" 
    x-transition 
    x-cloak
    class="fixed inset-0 flex items-center justify-center z-[9999]"
  >
    <!-- Overlay gelap -->
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>

    <!-- Konten Modal -->
    <div 
      @click.outside="$store.modal.openAdd = false"
      class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-[10000]"
    >
      <h3 class="text-lg font-semibold mb-4 text-gray-700">Tambah Akun</h3>
      <form method="POST" action="{{ route('accounts.store') }}">
        @csrf
        <div class="mb-3">
          <label class="block text-sm font-medium text-gray-600">Kode Akun</label>
          <input type="text" name="kode_akun" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
        </div>

        <div class="mb-3">
          <label class="block text-sm font-medium text-gray-600">Nama Akun</label>
          <input type="text" name="nama_akun" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
        </div>

        <div class="mb-3">
          <label class="block text-sm font-medium text-gray-600">Jenis Akun</label>
          <select name="jenis_akun" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
            <option value="">-- Pilih Jenis Akun --</option>
            @foreach(['aset lancar','aset tetap','liabilitas','ekuitas','pendapatan','beban'] as $jenis)
              <option value="{{ $jenis }}">{{ ucfirst($jenis) }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="block text-sm font-medium text-gray-600">Saldo Normal</label>
          <select name="saldo_normal" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
            <option value="debit">Debit</option>
            <option value="kredit">Kredit</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="block text-sm font-medium text-gray-600">Saldo Awal</label>
          <input type="text" name="saldo_awal" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
        </div>

        <div class="flex justify-end space-x-2 mt-4">
          <button type="button" @click="$store.modal.openAdd = false" 
            class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700">Batal</button>
          <button type="submit"
            class="inline-block px-6 py-3 font-bold text-center uppercase align-middle transition-all bg-transparent border rounded-lg cursor-pointer border-fuchsia-500 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-fuchsia-500">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- Modal Edit Akun --}}
  @foreach($accounts as $account)
  <div 
    x-show="$store.modal.openEdit === '{{ $account->id }}'" 
    x-transition 
    x-cloak
    class="fixed inset-0 flex items-center justify-center z-[9999]"
  >
    <!-- Overlay gelap -->
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>

    <!-- Konten Modal -->
    <div 
      @click.outside="$store.modal.openEdit = null"
      class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-[10000]"
    >
      <h3 class="text-lg font-semibold mb-4 text-gray-700">Edit Akun</h3>
      <form method="POST" action="{{ route('accounts.update', $account->id) }}">
        @csrf @method('PUT')

        <div class="mb-3">
          <label class="block text-sm font-medium text-gray-600">Nama Akun</label>
          <input type="text" name="nama_akun" value="{{ $account->nama_akun }}"
            class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
        </div>

        <div class="mb-3">
          <label class="block text-sm font-medium text-gray-600">Jenis Akun</label>
          <select name="jenis_akun" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
            @foreach(['aset lancar','aset tetap','liabilitas','ekuitas','pendapatan','beban'] as $jenis)
              <option value="{{ $jenis }}" {{ $account->jenis_akun == $jenis ? 'selected' : '' }}>
                {{ ucfirst($jenis) }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="block text-sm font-medium text-gray-600">Saldo Normal</label>
          <select name="saldo_normal" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
            <option value="debit" {{ $account->saldo_normal == 'debit' ? 'selected' : '' }}>Debit</option>
            <option value="kredit" {{ $account->saldo_normal == 'kredit' ? 'selected' : '' }}>Kredit</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="block text-sm font-medium text-gray-600">Saldo Awal</label>
          <input type="text" name="saldo_awal" value="{{ $account->saldo_awal }}"
            class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
        </div>

        <div class="flex justify-end space-x-2 mt-4">
          <button type="button" @click="$store.modal.openEdit = null"
            class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700">Batal</button>
          <button type="submit"
            class="mr-3 inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-red-500 to-yellow-400 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
  @endforeach
</div>
@endsection
