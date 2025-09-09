<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Mapel Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('guru-piket.mapel.store') }}">
                        @csrf
                        <div>
                            <x-input-label for="nama_mapel" value="Nama Mata Pelajaran" />
                            <x-text-input id="nama_mapel" class="block mt-1 w-full" type="text" name="nama_mapel"
                                :value="old('nama_mapel')" required autofocus />
                            <x-input-error :messages="$errors->get('nama_mapel')" class="mt-2" />
                        </div>

                        {{-- <div class="mt-4">
                            <x-input-label for="status" value="Status" />
                            <select name="status" id="status"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="mapel" {{ old('status') == 'mapel' ? 'selected' : '' }}>Mata Pelajaran
                                </option>
                                <option value="kosong" {{ old('status') == 'kosong' ? 'selected' : '' }}>Jam Kosong
                                    (Istirahat, dll)</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div> --}}
                        
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
            </div>
        </div>
    </div>
</x-app-layout>
