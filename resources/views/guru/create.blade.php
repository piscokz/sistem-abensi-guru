<x-app-layout>
    {{-- Ini adalah slot untuk header/judul halaman, umum di layout Breeze --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Guru Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('guru-piket.guru.store') }}" method="POST">
                        {{-- Memanggil file partial form yang sama --}}
                        {{-- Kode di dalam _form.blade.php tidak perlu diubah --}}
                        @include('guru._form', ['tombol' => 'Simpan', 'guru' => new \App\Models\Guru])
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>