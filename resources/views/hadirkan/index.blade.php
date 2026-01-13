<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
            Hadirkan Guru Mapel Hari Ini
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @elseif (session('info'))
                <div class="bg-blue-100 text-blue-700 p-3 rounded mb-4">
                    {{ session('info') }}
                </div>
            @elseif (session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Guru Belum Absen Hari {{ $hari }}</h3>

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="p-3">#</th>
                                <th class="p-3">Nama Guru</th>
                                <th class="p-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($guruHariIni as $index => $guru)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="p-3">{{ $index + 1 }}</td>
                                    <td class="p-3">{{ $guru->nama_guru }}</td>
                                    <td class="p-3">
                                        <form action="{{ route('guru-piket.hadirkan.store', $guru->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                                Hadirkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-3 text-center text-gray-500">Semua guru sudah absen hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
