<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Guru') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <div class="bg-white p-6 shadow rounded-lg">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nama Guru</label>
                <p class="mt-1 text-gray-600">{{ $guru->nama_guru }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">NIP</label>
                <p class="mt-1 text-gray-600">{{ $guru->nip }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-gray-600">{{ $guru->user->email }}</p>
            </div>

            {{-- <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <p class="mt-1 text-gray-600">{{ $guru->nomor_telepon }}</p>
            </div> --}}

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tanggal Bergabung</label>
                <p class="mt-1 text-gray-600">{{ $guru->created_at->format('d-m-Y') }}</p>
            </div>

            {{-- <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Mata Pelajaran yang Diajar</label>
                @if($guru->mapels->isEmpty())
                    <p class="mt-1 text-gray-600">Tidak ada mata pelajaran yang diajar.</p>
                @else
                    <ul class="list-disc list-inside mt-1 text-gray-600">
                        @foreach($guru->mapels as $mapel)
                            <li>{{ $mapel->nama_mapel }}</li>
                        @endforeach
                    </ul>
                @endif
            </div> --}}

            <div class="flex justify-end">
                <a href="{{ route('guru-piket.guru.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg">Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>
