<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal untuk {{ $kelas->nama_kelas }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">

                <a href="{{ route('guru-piket.kelas.jadwal.create', $kelas->id) }}"
                    class="inline-block px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 mb-4">
                    Tambah Jadwal
                </a>

                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 border">#</th>
                            <th class="px-4 py-2 border">Nama Jadwal</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwals as $jadwal)
                            <tr>
                                <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 border">
                                    {{ $jadwal->shifts->pluck('nama')->join(', ') ?: '-' }}
                                </td>
                                <td class="px-4 py-2 border">
                                    @if ($jadwal->is_active)
                                        <span class="text-green-600 font-semibold">Aktif</span>
                                    @else
                                        <span class="text-gray-500">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border space-x-2">
                                    <a href="{{ route('guru-piket.jadwal.details.index', $jadwal->id) }}"
                                        class="text-blue-600 hover:underline">Detail jadwal</a>
                                    <a href="{{ route('guru-piket.kelas.jadwal.edit', [$kelas->id, $jadwal->id]) }}"
                                        class="text-indigo-600 hover:underline">Edit</a>
                                    <form action="{{ route('guru-piket.kelas.jadwal.destroy', [$kelas->id, $jadwal->id]) }}"
                                        method="POST" class="inline"
                                        onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                                    Belum ada jadwal.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
