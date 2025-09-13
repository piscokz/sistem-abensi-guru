<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jam Mapel - Shift {{ $shift->nama }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <a href="{{ route('guru-piket.shift.jam-mapel.create', $shift->id) }}"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Tambah Jam Mapel
                </a>

                @if (session('success'))
                    <div class="mt-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="min-w-full mt-4 border divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">No</th>
                            <th class="px-4 py-2 text-left">Jam</th>
                            <th class="px-4 py-2 text-left">Keterangan</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jamMapels as $jm)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $jm->nomor_jam }}</td>
                                <td class="px-4 py-2">{{ $jm->jam_mulai }} - {{ $jm->jam_selesai }}</td>
                                <td class="px-4 py-2">{{ $jm->keterangan ?? '-' }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    {{-- EDIT pakai route shallow --}}
                                    <a href="{{ route('guru-piket.jam-mapel.edit', $jm->id) }}"
                                        class="text-indigo-600 hover:underline">Edit</a>

                                    {{-- HAPUS pakai route shallow --}}
                                    <form action="{{ route('guru-piket.jam-mapel.destroy', $jm->id) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Hapus jam mapel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-center text-gray-500">Belum ada jam mapel.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
