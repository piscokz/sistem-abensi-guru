<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Jam Mapel - Shift {{ $shift->nama }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
            {{-- UPDATE pakai shallow --}}
            <form action="{{ route('guru-piket.jam-mapel.update', $jamMapel->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('jam_mapel._form', ['tombol' => 'Update'])
            </form>
        </div>
    </div>
</x-app-layout>
