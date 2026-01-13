<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100">
            Manajemen Shift
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Notifikasi --}}
            @if (session('success'))
                <div x-data="{ show: true }" 
                     x-show="show"
                     class="mb-6 flex justify-between items-center bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-100 border border-green-300 dark:border-green-700 px-4 py-3 rounded-lg shadow-sm"
                     role="alert">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="ml-4 font-bold hover:text-green-900 dark:hover:text-green-300">×</button>
                </div>
            @endif

            {{-- Card utama --}}
            <div class="bg-white dark:bg-slate-800 shadow-lg rounded-2xl p-6 space-y-6">
                
                {{-- Tombol tambah --}}
                <div class="flex justify-start">
                    <a href="{{ route('guru-piket.shift.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white text-sm font-semibold rounded-lg shadow hover:bg-slate-700 transition-all duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Shift
                    </a>
                </div>

                {{-- Daftar Shift --}}
                @if ($shifts->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-6">
                        Belum ada shift yang terdaftar.
                    </p>
                @else
                    {{-- Tampilan desktop --}}
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <thead class="bg-gray-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-200 border-b">Nama Shift</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-200 border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($shifts as $shift)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                        <td class="px-4 py-3 text-gray-800 dark:text-gray-100">
                                            {{ $shift->nama }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-3">
                                                <a href="{{ route('guru-piket.shift.jam-mapel.index', $shift->id) }}"
                                                   class="text-blue-600 dark:text-blue-400 hover:underline">Jam Mapel</a>
                                                <a href="{{ route('guru-piket.shift.edit', $shift->id) }}"
                                                   class="text-indigo-600 dark:text-indigo-400 hover:underline">Edit</a>
                                                <form action="{{ route('guru-piket.shift.destroy', $shift->id) }}" 
                                                      method="POST"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus shift ini? (semua data yang berhubungan akan dihapus)');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Tampilan mobile (card) --}}
                    <div class="space-y-4 sm:hidden">
                        @foreach ($shifts as $shift)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm bg-white dark:bg-slate-800">
                                <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">
                                    {{ $shift->nama }}
                                </h3>
                                <div class="mt-3 flex flex-wrap gap-3 text-sm">
                                    <a href="{{ route('guru-piket.shift.jam-mapel.index', $shift->id) }}"
                                       class="text-blue-600 dark:text-blue-400 font-medium hover:underline">Jam Mapel</a>
                                    <a href="{{ route('guru-piket.shift.edit', $shift->id) }}"
                                       class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">Edit</a>
                                    <form action="{{ route('guru-piket.shift.destroy', $shift->id) }}" 
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus shift ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 font-medium hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
