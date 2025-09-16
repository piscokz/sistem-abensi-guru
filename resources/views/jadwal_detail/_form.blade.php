@csrf
<div class="mb-4">
    <label for="hari" class="block text-sm font-medium text-gray-700">Hari</label>
    <input type="text" name="hari" id="hari" value="{{ old('hari', $hari ?? $detail->hari ?? '') }}"
        readonly
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
</div>

<div class="mb-4">
    <label for="jam_mapel_id" class="block text-sm font-medium text-gray-700">Jam Mapel</label>
    <input type="text" value="{{ $jamMapel->nomor_jam ?? '' }} ({{ $jamMapel->jam_mulai ?? '' }} - {{ $jamMapel->jam_selesai ?? '' }})"
        readonly class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
    <input type="hidden" name="jam_mapel_id" value="{{ old('jam_mapel_id', $jam_mapel_id ?? $detail->jam_mapel_id ?? '') }}">
</div>

<div class="mb-4">
    <label for="mapel_id" class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
    <select name="mapel_id" id="mapel_id"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @foreach ($mapels as $mapel)
            <option value="{{ $mapel->id }}"
                @selected(old('mapel_id', $detail->mapel_id ?? '') == $mapel->id)>
                {{ $mapel->nama_mapel }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label for="guru_id" class="block text-sm font-medium text-gray-700">Guru</label>
    <select name="guru_id" id="guru_id"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @foreach ($gurus as $guru)
            <option value="{{ $guru->id }}"
                @selected(old('guru_id', $detail->guru_id ?? '') == $guru->id)>
                {{ $guru->nama_guru }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <button type="submit"
        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">{{ $tombol }}</button>
</div>
