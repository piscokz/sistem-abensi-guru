<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Shift</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
            <form action="{{ route('guru-piket.shift.update', $shift->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('shift._form', ['tombol' => 'Update'])
            </form>
        </div>
    </div>
</x-app-layout>
