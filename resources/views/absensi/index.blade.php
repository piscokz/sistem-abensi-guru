<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Absensi Guru
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            
    <!-- Filter -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6 flex justify-center">
    <form method="GET" action="{{ route('guru-piket.absensi') }}" 
          class="w-full max-w-4xl space-y-6 text-center">
          
        <!-- Baris pertama: input filter -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 justify-center">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 text-left">
                    Guru
                </label>
                <select name="guru_id"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                           dark:bg-gray-900 dark:text-gray-100 
                           focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Semua Guru --</option>
                    @foreach ($gurus as $g)
                        <option value="{{ $g->id }}" {{ request('guru_id') == $g->id ? 'selected' : '' }}>
                            {{ $g->nama_guru }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 text-left">
                    Tanggal Dari
                </label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') ?? '' }}"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                           dark:bg-gray-900 dark:text-gray-100 
                           focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 text-left">
                    Tanggal Sampai
                </label>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') ?? '' }}"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                           dark:bg-gray-900 dark:text-gray-100 
                           focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <!-- Baris kedua: tombol di tengah -->
        <div class="flex flex-col sm:flex-row flex-wrap gap-3 justify-center pt-3">
            <button type="submit"
                class="flex-1 sm:flex-none px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 
                       text-white font-semibold rounded-lg shadow-sm hover:shadow-md 
                       transition active:scale-95">
                🔍 Filter
            </button>
            <a href="{{ route('guru-piket.absensi') }}"
                class="flex-1 sm:flex-none px-6 py-2.5 bg-gray-100 hover:bg-gray-200 
                       text-gray-800 font-semibold rounded-lg shadow-sm hover:shadow-md 
                       transition active:scale-95">
                ♻️ Reset
            </a>
            <a href="{{ route('guru-piket.absensi.export.pdf', request()->query()) }}"
                class="flex-1 sm:flex-none px-6 py-2.5 bg-rose-600 hover:bg-rose-700 
                       text-white font-semibold rounded-lg shadow-sm hover:shadow-md 
                       transition active:scale-95">
                🧾 Ekspor PDF
            </a>
        </div>
    </form>
</div>




            <!-- Data Table -->
@if(session('warning'))
    <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md text-yellow-700">
        {{ session('warning') }}
    </div>
@endif


            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300 mb-4">
                    <div>Menampilkan {{ $absensis->total() }} entri. Halaman {{ $absensis->currentPage() }} dari {{ $absensis->lastPage() }}.</div>
                    <div class="font-semibold text-gray-800 dark:text-gray-100">Total Jam Kerja: {{ $total_jam ?? 0 }} Pertemuan</div>
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jam</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Guru Mapel</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kelas</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Via</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($absensis as $absen)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        {{ ($absensis->currentPage() - 1) * $absensis->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        {{ optional(\Carbon\Carbon::parse($absen->waktu_absen))->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $absen->jadwalDetail->jamMapel->nomor_jam ?? '-' }}
                                        ({{ $absen->jadwalDetail->jamMapel->jam_mulai ?? '-' }} -
                                        {{ $absen->jadwalDetail->jamMapel->jam_selesai ?? '-' }})
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        {{ optional($absen->guru)->nama_guru }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $absen->jadwalDetail->jadwal->kelas->nama_kelas }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ isset($absen->waktu_absen) ? \Carbon\Carbon::parse($absen->waktu_absen)->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ ucfirst($absen->via) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($absen->status === 'hadir')
                                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                Hadir
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                Tidak Hadir
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Belum ada data absensi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card List -->
                <div class="md:hidden space-y-4">
                    @forelse($absensis as $absen)
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                                {{ optional(\Carbon\Carbon::parse($absen->tanggal))->format('d M Y') }}
                            </p>
                            <p class="text-gray-800 dark:text-gray-100 font-semibold text-base">
                                {{ optional($absen->guru)->nama_guru ?? '-' }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                Jam {{ $absen->jadwalDetail->jamMapel->nomor_jam ?? '-' }}
                                ({{ $absen->jadwalDetail->jamMapel->jam_mulai ?? '-' }} -
                                {{ $absen->jadwalDetail->jamMapel->jam_selesai ?? '-' }})
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                Waktu: {{ optional($absen->created_at)->format('H:i:s') }}
                            </p>
                            <p class="mt-2">
                                Via: {{ ucfirst($absen->via) }}
                            </p>
                            <p class="mt-2">
                                @if($absen->status === 'hadir')
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        Hadir
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        Tidak Hadir
                                    </span>
                                @endif
                            </p>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-6 text-sm">
                            Belum ada data absensi.
                        </p>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $absensis->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
