<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hasil Scan QR
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 text-center">

                @if ($status === 'success')
                    <h3 class="text-lg font-semibold text-green-600 mb-2">{{ $message }}</h3>
                    <p class="text-gray-700">Guru: <strong>{{ $guru->nama_guru }}</strong></p>
                    <p class="text-gray-700">Kelas: <strong>{{ $kelas->nama_kelas ?? '-' }}</strong></p>
                    <p class="text-gray-700">Mapel: <strong>{{ $mapel->nama_mapel ?? '-' }}</strong></p>
                    {{-- <p class="text-gray-700">Waktu: {{ $absen->waktu_absen->format('d M Y H:i:s') }}</p> --}}
                @else
                    <h3 class="text-lg font-semibold text-red-600 mb-2">{{ $message }}</h3>
                @endif

                <div class="mt-6">
                    <a href="{{ route('guru-mapel.scan-qr.index') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Kembali ke Scanner
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
