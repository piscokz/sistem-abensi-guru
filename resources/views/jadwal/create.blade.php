<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Jadwal Baru') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('guru-piket.jadwal.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700">Kelas</label>
                    <select name="kelas_id" class="w-full border-gray-300 rounded-lg">
                        @foreach ($daftarKelas as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran"
                           class="w-full border-gray-300 rounded-lg"
                           placeholder="2025/2026" required>
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" value="1" class="rounded">
                        <span class="ml-2">Jadikan Jadwal Aktif</span>
                    </label>
                </div>

                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Simpan
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
