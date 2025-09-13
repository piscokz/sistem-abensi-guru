<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tambah Detail Jadwal</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('guru-piket.jadwal_detail.store', $jadwal->id) }}" method="POST">
                @include('jadwal_detail._form', ['tombol' => 'Simpan', 'detail' => new \App\Models\JadwalDetail()])
            </form>
        </div>
    </div>
</x-app-layout>
