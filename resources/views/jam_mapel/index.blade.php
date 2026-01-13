<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            Jam Mapel – Shift {{ $shift->nama }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Notifikasi sukses --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show"
                    class="mb-6 flex justify-between items-center bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-100 border border-green-300 dark:border-green-700 px-4 py-3 rounded-lg shadow-sm"
                    role="alert">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="ml-4 font-bold hover:text-green-900 dark:hover:text-green-300">×</button>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 shadow-lg rounded-2xl p-6">
                {{-- Tombol tambah --}}
                <div class="flex justify-start mb-6">
                    <a href="{{ route('guru-piket.shift.jam-mapel.create', $shift->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-indigo-700 transition-all duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Jam Mapel
                    </a>
                </div>

                {{-- Daftar Jam Mapel --}}
                @if ($jamMapels->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-6">
                        Belum ada jam mapel yang terdaftar.
                    </p>
                @else
                    {{-- Tabel desktop --}}
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="min-w-full border border-gray-200 dark:border-gray-700 text-sm rounded-lg overflow-hidden">
                            <thead class="bg-gray-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-200 border-b">No</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-200 border-b">Jam</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-200 border-b">Keterangan</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-200 border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($jamMapels as $jm)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                        <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $jm->nomor_jam }}</td>
                                        <td class="px-4 py-2 text-gray-800 dark:text-gray-100">
                                            {{ $jm->jam_mulai }} – {{ $jm->jam_selesai }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-800 dark:text-gray-100">
                                            {{ $jm->keterangan ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2">
                                            <div class="flex flex-wrap gap-3">
                                                <a href="{{ route('guru-piket.jam-mapel.edit', $jm->id) }}"
                                                   class="text-indigo-600 dark:text-indigo-400 hover:underline">Edit</a>
                                                <form action="{{ route('guru-piket.jam-mapel.destroy', $jm->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Hapus jam mapel ini?')">
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

                    {{-- Tampilan mobile --}}
                    <div class="space-y-4 sm:hidden mt-4">
                        @foreach ($jamMapels as $jm)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm bg-white dark:bg-slate-800">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">
                                        Jam {{ $jm->nomor_jam }}
                                    </h3>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $jm->jam_mulai }} – {{ $jm->jam_selesai }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Keterangan:</span> {{ $jm->keterangan ?? '-' }}
                                </p>
                                <div class="mt-3 flex gap-3 text-sm">
                                    <a href="{{ route('guru-piket.jam-mapel.edit', $jm->id) }}"
                                       class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">Edit</a>
                                    <form action="{{ route('guru-piket.jam-mapel.destroy', $jm->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus jam mapel ini?')">
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
