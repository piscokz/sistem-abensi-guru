<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Manajemen Shift</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <a href="{{ route('guru-piket.shift.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Tambah Shift</a>

            @if(session('success'))
                <div class="mt-4 bg-green-100 text-green-700 px-4 py-2 rounded">{{ session('success') }}</div>
            @endif

            <table class="min-w-full mt-4 border text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border px-2 py-2">Nama Shift</th>
                        <th class="border px-2 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shifts as $shift)
                        <tr>
                            <td class="border px-2 py-2">{{ $shift->nama }}</td>
                            <td class="border px-2 py-2 flex space-x-2">
                                <a href="{{ route('guru-piket.shift.jam-mapel.index', $shift->id) }}" class="text-blue-600">Jam mapel</a>
                                <a href="{{ route('guru-piket.shift.edit', $shift->id) }}" class="text-indigo-600">Edit</a>
                                <form action="{{ route('guru-piket.shift.destroy', $shift->id) }}" method="POST" onsubmit="return confirm('Hapus shift ini?');">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center py-2 text-gray-500">Belum ada shift</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
