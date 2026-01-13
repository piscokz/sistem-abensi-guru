<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
            Kelola Tanggal Libur / Tanggal Merah
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl p-6 space-y-6">

                {{-- Notifikasi --}}
                @if (session('success'))
                    <div class="p-3 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100 rounded-lg text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Form Tambah Libur --}}
                <form method="POST" action="{{ route('guru-piket.libur.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="tanggal" :value="__('Tanggal')" />
                        <x-text-input id="tanggal" name="tanggal" type="date"
                                      class="mt-1 block w-full"
                                      value="{{ old('tanggal') }}" required autofocus />
                        @error('tanggal')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-input-label for="keterangan" :value="__('Keterangan (opsional)')" />
                        <x-text-input id="keterangan" name="keterangan" type="text"
                                      class="mt-1 block w-full"
                                      placeholder="Contoh: Hari Raya, Libur Nasional"
                                      value="{{ old('keterangan') }}" />
                    </div>

                    <x-primary-button class="mt-3">
                        Tambah Tanggal Libur
                    </x-primary-button>
                </form>

                {{-- Daftar Libur --}}
                <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                        Daftar Tanggal Libur
                    </h3>

                    @if ($liburs->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada tanggal libur yang ditetapkan.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-300">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 font-medium">Tanggal</th>
                                        <th class="px-4 py-2 font-medium">Keterangan</th>
                                        <th class="px-4 py-2 text-center font-medium">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($liburs as $libur)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($libur->tanggal)->translatedFormat('d F Y') }}</td>
                                            <td class="px-4 py-2">{{ $libur->keterangan ?? '-' }}</td>
                                            <td class="px-4 py-2 text-center">
                                                <form method="POST" action="{{ route('guru-piket.libur.destroy', $libur) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-danger-button
                                                        onclick="return confirm('Yakin ingin menghapus tanggal libur ini?')">
                                                        Hapus
                                                    </x-danger-button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
