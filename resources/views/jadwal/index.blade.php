<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            Jadwal Kelas {{ $kelas->nama_kelas }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Tombol Tambah Jadwal --}}
            <div class="flex justify-end">
                <a href="{{ route('guru-piket.kelas.jadwal.create', $kelas->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" class="w-4 h-4 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Jadwal
                </a>
            </div>

            {{-- Notifikasi --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show"
                     class="flex items-center justify-between bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-100 border border-green-300 dark:border-green-700 px-4 py-3 rounded-lg shadow-sm"
                     role="alert">
                    <span class="font-medium">{{ session('success') }}</span>
                    <button @click="show = false" class="text-lg font-bold hover:text-green-900 dark:hover:text-green-200">&times;</button>
                </div>
            @endif

            {{-- Tampilan Mobile (Card View) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:hidden gap-4">
                @forelse ($jadwals as $jadwal)
                    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">
                                    {{ $jadwal->nama_jadwal }}
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                    Shift {{ $loop->iteration }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end gap-3 text-sm">
                            <a href="{{ route('guru-piket.jadwal.details.index', $jadwal->id) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 dark:bg-blue-800 dark:text-blue-200 rounded-md hover:bg-blue-200 dark:hover:bg-blue-700 transition">
                                Detail Jadwal
                            </a>
                            <form action="{{ route('guru-piket.kelas.jadwal.destroy', [$kelas->id, $jadwal->id]) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-200 rounded-md hover:bg-red-200 dark:hover:bg-red-700 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-slate-500 dark:text-slate-400 py-6">
                        Belum ada jadwal.
                    </div>
                @endforelse
            </div>

            {{-- Tampilan Desktop (Table View) --}}
            <div class="hidden lg:block bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Shift</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse ($jadwals as $jadwal)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-200">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-100">
                                        {{ $jadwal->nama_jadwal }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                                        <a href="{{ route('guru-piket.jadwal.details.index', $jadwal->id) }}"
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400">Detail</a>
                                        <form action="{{ route('guru-piket.kelas.jadwal.destroy', [$kelas->id, $jadwal->id]) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-6 text-center text-slate-500 dark:text-slate-400">
                                        Belum ada jadwal.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
