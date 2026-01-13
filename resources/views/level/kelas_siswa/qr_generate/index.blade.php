<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Generate QR Absensi
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6 text-center transition hover:shadow-xl">

                @if ($onGoing)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 leading-tight">
                            Kelas {{ $kelas->nama_kelas ?? '-' }}
                        </h3>
                        <p class="text-gray-700 text-m mt-1">
                            Jam Ke-{{ $onGoing->jamMapel->nomor_jam}}
                        </p>
                        <p class="text-gray-500 text-sm mt-1">
                            {{ $onGoing->mapel->nama_mapel ?? '-' }} — {{ $onGoing->guru->nama_guru ?? '-' }}
                        </p>
                    </div>

                    @if ($qrImage)
                        <div class="flex justify-center mb-6">
                            <div class="p-3 bg-gradient-to-br from-indigo-50 to-blue-100 rounded-2xl shadow-inner">
                                <img src="{{ $qrImage }}" alt="QR Code"
                                    class="w-56 h-56 sm:w-64 sm:h-64 border border-gray-200 bg-white rounded-xl shadow-md" />
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-gray-100 rounded-xl mb-6">
                            <p class="text-sm text-gray-600 mb-2">QR tidak tersedia (gunakan token teks).</p>
                            <code class="block text-gray-800 text-sm font-mono break-all bg-white p-2 rounded">{{ $qr->token ?? '' }}</code>
                        </div>
                    @endif

                    <div class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                        {{-- <p>
                            Token:
                            <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                {{ $qr->token }}
                            </span>
                        </p> --}}
                        <p>
                            Berlaku sampai:
                            <strong class="text-gray-800 dark:text-gray-100">
                                {{ $qr->expires_at->format('d M Y H:i') }}
                            </strong>
                        </p>
                    </div>
                @else
                    <div class="py-8">
                        <p class="text-lg font-medium mb-4 text-gray-700 dark:text-gray-200">
                            Tidak ada jadwal untuk kelas ini pada jam sekarang.
                        </p>
                        <a href="{{ route('kelas-siswa.jadwal.index') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-500 hover:bg-indigo-600 text-white font-medium rounded-xl shadow transition active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali ke Jadwal
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
