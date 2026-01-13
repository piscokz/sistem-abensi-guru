<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Rekap Absensi Hari Ini
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

            {{-- Pesan sukses --}}
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <table class="min-w-full border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <th class="py-2 px-3 border">Jam Ke</th>
                        <th class="py-2 px-3 border">Mapel</th>
                        <th class="py-2 px-3 border">Status</th>
                        <th class="py-2 px-3 border">Waktu Absen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($absensiHariIni as $absen)
                        <tr class="text-center border-t">
                            <td class="py-2 px-3 border">{{ $absen->jadwalDetail->jamMapel->nomor_jam }}</td>
                            <td class="py-2 px-3 border">{{ $absen->jadwalDetail->mapel->nama_mapel }}</td>
                            <td class="py-2 px-3 border capitalize">{{ $absen->status }}</td>
                            <td class="py-2 px-3 border">{{ $absen->waktu_absen->format('H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">
                                Belum ada data absensi hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
