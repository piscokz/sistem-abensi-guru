<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100">
            {{ __('Manajemen Mapel') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Notifikasi --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show"
                    class="flex items-center justify-between bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-100 border border-green-300 dark:border-green-700 px-4 py-3 rounded-lg shadow-sm"
                    role="alert">
                    <span class="font-medium">{{ session('success') }}</span>
                    <button @click="show = false" class="text-lg font-bold hover:text-green-900 dark:hover:text-green-200">&times;</button>
                </div>
            @endif





            {{-- Tampilan Kartu (Mobile) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:hidden gap-4">
                @forelse ($daftarMapel as $mapel)
                    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">
                                {{ $mapel->nama_mapel }}
                            </h3>
                            <div class="flex gap-3 text-sm">
                                <a href="{{ route('guru-piket.mapel.edit', $mapel->id) }}"
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800">Edit</a>
                                <form action="{{ route('guru-piket.mapel.destroy', $mapel->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus mapel ini? (semua data yang berhubungan akan dihapus)');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            ID: {{ $mapel->id }}
                        </div>
                    </div>
                @empty
                    <div class="text-center text-slate-500 dark:text-slate-400 py-8 col-span-full">
                        Tidak ada data Mapel.
                    </div>
                @endforelse
            </div>

                        {{-- Tombol Tambah --}}
    
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('guru-piket.mapel.store') }}">
                        @csrf
                        <div>
                            <x-input-label for="nama_mapel" value="Nama Mata Pelajaran" />
                            <x-text-input id="nama_mapel" class="block mt-1 w-full" type="text" name="nama_mapel"
                                :value="old('nama_mapel')" required autofocus />
                            <x-input-error :messages="$errors->get('nama_mapel')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('guru-piket.mapel.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Batal
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>


                    </form>
                </div>

            {{-- Tampilan Tabel (Desktop) --}}
            <div class="hidden lg:block bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Mapel</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse ($daftarMapel as $mapel)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-200">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-100">
                                        {{ $mapel->nama_mapel }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <a href="{{ route('guru-piket.mapel.edit', $mapel->id) }}"
                                            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">Edit</a>
                                        <form action="{{ route('guru-piket.mapel.destroy', $mapel->id) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus mapel ini? (semua data yang berhubungan akan dihapus)');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="ml-4 text-red-600 hover:text-red-800 dark:text-red-400">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-6 text-center text-slate-500 dark:text-slate-400">
                                        Tidak ada data Mapel.
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
