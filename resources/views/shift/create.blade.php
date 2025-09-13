<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Tambah Shift</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
            <form action="{{ route('guru-piket.shift.store') }}" method="POST">
                @include('shift._form', ['tombol' => 'Simpan'])
            </form>
        </div>
    </div>
</x-app-layout>
