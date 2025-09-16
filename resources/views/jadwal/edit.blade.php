<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Jadwal: {{ $jadwal->nama_jadwal }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form action="{{ route('guru-piket.kelas.jadwal.update', [$kelas->id, $jadwal->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('jadwal._form', ['tombol' => 'Update'])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
