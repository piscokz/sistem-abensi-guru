<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal {{ $jadwal->kelas->nama_kelas }} ({{ $jadwal->tahun_ajaran }})
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

            @if (session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <h3 class="text-lg font-bold mb-4">Tambah Detail Jadwal</h3>

            <form method="POST" action="{{ route('guru-piket.jadwal_detail.store', $jadwal->id) }}" class="mb-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block font-medium text-sm text-gray-700">Guru</label>
                        <select id="guru_id" name="guru_id" class="w-full border-gray-300 rounded-lg">
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($daftarGuru as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama_guru }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Mapel</label>
                        <select id="mapel_id" name="mapel_id" class="w-full border-gray-300 rounded-lg">
                            <option value="">-- Pilih Mapel --</option>
                            {{-- opsi diisi lewat AJAX --}}
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Slot</label>
                        <select name="slot_id" class="w-full border-gray-300 rounded-lg">
                            @foreach ($daftarSlot as $slot)
                                <option value="{{ $slot->id }}">Slot {{ $slot->nomor_slot }} ({{ $slot->jam_mulai }}
                                    - {{ $slot->jam_selesai }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Hari</label>
                        <select name="hari" class="w-full border-gray-300 rounded-lg">
                            <option>Senin</option>
                            <option>Selasa</option>
                            <option>Rabu</option>
                            <option>Kamis</option>
                            <option>Jumat</option>
                            <option>Sabtu</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Tambah
                </button>
            </form>


            <h3 class="text-lg font-bold mb-2">Detail Jadwal</h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Hari</th>
                        <th class="px-4 py-2">Slot</th>
                        <th class="px-4 py-2">Mapel</th>
                        <th class="px-4 py-2">Guru</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwal->details as $detail)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $detail->hari }}</td>
                            <td class="px-4 py-2">Slot {{ $detail->slot->nomor_slot }} ({{ $detail->slot->jam_mulai }}
                                - {{ $detail->slot->jam_selesai }})</td>
                            <td class="px-4 py-2">{{ $detail->mapel->nama_mapel }}</td>
                            <td class="px-4 py-2">{{ $detail->guru->nama_guru }}</td>
                            <td class="px-4 py-2">
                                <form method="POST"
                                    action="{{ route('guru-piket.jadwal_detail.destroy', [$jadwal->id, $detail->id]) }}"
                                    onsubmit="return confirm('Hapus detail jadwal ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500">Belum ada detail jadwal</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
    @push('scripts')
        <script>
            document.getElementById('guru_id').addEventListener('change', function() {
                let guruId = this.value;
                let mapelSelect = document.getElementById('mapel_id');

                mapelSelect.innerHTML = '<option value="">-- Memuat... --</option>';

                if (guruId) {
                    fetch(`/jadwal/get-mapel/${guruId}`)
                        .then(response => response.json())
                        .then(data => {
                            mapelSelect.innerHTML = '<option value="">-- Pilih Mapel --</option>';
                            data.forEach(mapel => {
                                let option = document.createElement('option');
                                option.value = mapel.id;
                                option.textContent = mapel.nama_mapel;
                                mapelSelect.appendChild(option);
                            });
                        });
                } else {
                    mapelSelect.innerHTML = '<option value="">-- Pilih Mapel --</option>';
                }
            });
        </script>
    @endpush
</x-app-layout>
