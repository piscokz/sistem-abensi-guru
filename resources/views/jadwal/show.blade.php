<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Edit Jadwal</h2></x-slot>
    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            <form action="{{ route('guru-piket.jadwal.update', $jadwal->id) }}" method="POST">
                @csrf @method('PUT')
                @include('jadwal._form', ['tombol' => 'Update', 'jadwal' => $jadwal])
            </form>
        </div>
    </div>
</x-app-layout>
