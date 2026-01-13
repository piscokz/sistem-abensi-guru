<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Manajemen Kelas') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Tombol Tambah Kelas --}}
            <div class="flex justify-end">
                <a href="{{ route('guru-piket.kelas.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" class="w-4 h-4 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Kelas
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
                @forelse ($daftarKelas as $kelas)
                    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">
                                    {{ $kelas->nama_kelas }}
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                    {{ $kelas->user->email }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end gap-3 text-sm">
                            <a href="{{ route('guru-piket.kelas.jadwal.index', ['kelas' => $kelas->id]) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200 rounded-md hover:bg-green-200 dark:hover:bg-green-700 transition">
                                Detail Jadwal
                            </a>
                            <a href="{{ route('guru-piket.kelas.edit', $kelas->id) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 dark:bg-indigo-800 dark:text-indigo-200 rounded-md hover:bg-indigo-200 dark:hover:bg-indigo-700 transition">
                                Edit
                            </a>
                            <form action="{{ route('guru-piket.kelas.destroy', $kelas->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');">
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
                        Tidak ada data kelas.
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
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse ($daftarKelas as $kelas)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-200">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-100">
                                        {{ $kelas->nama_kelas }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                        {{ $kelas->user->email }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <a href="{{ route('guru-piket.kelas.jadwal.index', ['kelas' => $kelas->id]) }}"
                                            class="text-green-600 hover:text-green-800 dark:text-green-400">Detail Jadwal</a>
                                        <a href="{{ route('guru-piket.kelas.edit', $kelas->id) }}"
                                            class="ml-4 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">Edit</a>
                                        <form action="{{ route('guru-piket.kelas.destroy', $kelas->id) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="ml-4 text-red-600 hover:text-red-800 dark:text-red-400">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-6 text-center text-slate-500 dark:text-slate-400">
                                        Tidak ada data kelas.
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
