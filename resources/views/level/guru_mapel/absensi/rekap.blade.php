<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
            Rekap Absensi Hari Ini
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-6 space-y-6">

                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                        {{ $guru->nama_guru }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Hari: {{ ucfirst($hari) }}
                    </p>
                </div>

                @if ($rekap->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400 text-center py-6">
                        Tidak ada jadwal mengajar untuk hari ini.
                    </p>
                @else
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full border-collapse rounded-lg overflow-hidden">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr class="text-left text-gray-700 dark:text-gray-300">
                                    <th class="px-4 py-3 font-medium">Kelas</th>
                                    <th class="px-4 py-3 font-medium">Mapel</th>
                                    <th class="px-4 py-3 font-medium">Jam Ke</th>
                                    <th class="px-4 py-3 font-medium">Waktu Absen</th>
                                    <th class="px-4 py-3 font-medium">Via</th>
                                    <th class="px-4 py-3 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($rekap as $r)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-4 py-3 text-gray-800 dark:text-gray-200">{{ $r['kelas'] }}</td>
                                        <td class="px-4 py-3 text-gray-800 dark:text-gray-200">{{ $r['mapel'] }}</td>
                                        <td class="px-4 py-3 text-gray-800 dark:text-gray-200 text-center">{{ $r['jam'] }}</td>
                                        <td class="px-4 py-3 text-gray-800 dark:text-gray-200">
                                            {{ $r['waktu_absen'] !== '-' ? \Carbon\Carbon::parse($r['waktu_absen'])->format('H:i:s') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-800 dark:text-gray-200 text-center">
                                            {{ ucfirst($r['via'] ?? '-') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if (strtolower($r['status']) === 'hadir')
                                                <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full dark:bg-green-800 dark:text-green-200">
                                                    Hadir
                                                </span>
                                            @elseif (strtolower($r['status']) === 'tidak_hadir')
                                                <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full dark:bg-red-800 dark:text-red-200">
                                                    Tidak Hadir
                                                </span>
                                            @else
                                                <span class="px-3 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-200">
                                                    Belum Diisi
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-4">
                        @foreach ($rekap as $r)
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-xl shadow-sm">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-semibold text-gray-800 dark:text-gray-100">{{ $r['kelas'] }}</h4>
                                    @if (strtolower($r['status']) === 'hadir')
                                        <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full dark:bg-green-800 dark:text-green-200">
                                            Hadir
                                        </span>
                                    @elseif (strtolower($r['status']) === 'tidak_hadir')
                                        <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full dark:bg-red-800 dark:text-red-200">
                                            Tidak
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-200">
                                            Belum
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    <strong>Mapel:</strong> {{ $r['mapel'] }}
                                </p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    <strong>Jam ke:</strong> {{ $r['jam'] }}
                                </p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    <strong>Waktu Absen:</strong> 
                                    {{ $r['waktu_absen'] !== '-' ? \Carbon\Carbon::parse($r['waktu_absen'])->format('H:i:s') : '-' }}
                                </p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    <strong>Via:</strong> {{ ucfirst($r['via'] ?? '-') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
