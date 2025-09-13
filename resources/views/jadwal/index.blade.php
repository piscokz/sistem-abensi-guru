<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Manajemen Jadwal</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            
            <a href="{{ route('guru-piket.jadwal.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Tambah Jadwal</a>
            
            @if(session('success'))
                <div class="mt-4 bg-green-100 text-green-700 px-4 py-2 rounded">{{ session('success') }}</div>
            @endif

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full border text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border px-2 py-2">Kelas</th>
                            <th class="border px-2 py-2">Tahun Ajaran</th>
                            <th class="border px-2 py-2">Status</th>
                            <th class="border px-2 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $jadwal)
                            <tr>
                                <td class="border px-2 py-2">{{ $jadwal->kelas->nama_kelas }}</td>
                                <td class="border px-2 py-2">{{ $jadwal->tahun_ajaran }}</td>
                                <td class="border px-2 py-2">
                                    @if($jadwal->is_active)
                                        <span class="text-green-600 font-semibold">Aktif</span>
                                    @else
                                        <span class="text-gray-500">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="border px-2 py-2 flex space-x-2">
                                    <a href="{{ route('guru-piket.jadwal_detail.index', $jadwal->id) }}" class="text-blue-600">Detail</a>
                                    <a href="{{ route('guru-piket.jadwal.edit', $jadwal->id) }}" class="text-indigo-600">Edit</a>
                                    <form action="{{ route('guru-piket.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-2 text-gray-500">Belum ada jadwal</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
