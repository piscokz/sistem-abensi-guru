<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Tambah Jam Mapel - Shift {{ $shift->nama }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
            {{-- STORE pakai nested --}}
            <form action="{{ route('guru-piket.shift.jam-mapel.store', $shift->id) }}" method="POST">
                @include('jam_mapel._form', ['tombol' => 'Simpan'])
            </form>
        </div>
    </div>
</x-app-layout>
