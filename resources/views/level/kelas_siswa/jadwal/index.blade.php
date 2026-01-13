{{-- resources/views/level/kelas/jadwal/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal Kelas {{ $kelas->nama_kelas ?? '' }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg border border-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Tabel untuk layar besar --}}
                <div class="hidden sm:block overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-600 border-b">No</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-600 border-b">Shift</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-600 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($jadwals as $jadwal)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $jadwal->nama_jadwal }}</td>
                                    <td class="px-4 py-3 space-x-4">
                                        {{-- Detail dengan ikon kalender --}}
                                        <a href="{{ route('kelas-siswa.jadwal.detail', $jadwal->id) }}"
                                            class="inline-flex items-center text-blue-600 hover:underline">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-gray-500">Belum ada jadwal.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Card view untuk mobile --}}
                <div class="sm:hidden space-y-4">
                    @forelse ($jadwals as $jadwal)
                        <div class="border rounded-xl p-4 shadow-sm hover:shadow-md transition">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs text-gray-500">No: {{ $loop->iteration }}</span>
                                <span class="px-2 py-1 text-xs rounded-full bg-indigo-50 text-indigo-600">
                                    Shift
                                </span>
                            </div>
                            <div class="text-base font-semibold text-gray-800">
                                {{ $jadwal->nama_jadwal }}
                            </div>
                            <div class="mt-4 flex flex-col gap-2">
                                {{-- Detail dengan ikon kalender, tombol panjang --}}
                                <a href="{{ route('kelas-siswa.jadwal.detail', $jadwal->id) }}"
                                    class="inline-flex items-center w-full justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Detail Jadwal
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500">Belum ada jadwal.</div>
                    @endforelse
                </div>


            </div>
        </div>
    </div>
</x-app-layout>
