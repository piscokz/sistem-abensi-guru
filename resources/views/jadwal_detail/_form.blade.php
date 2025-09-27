@csrf
<div class="mb-4">
    <label for="hari" class="block text-sm font-medium text-gray-700">Hari</label>
    @if (isset($hari))
        {{-- Mode edit/createWithJam: hari fix --}}
        <input type="text" name="hari" id="hari" value="{{ $detail->hari ?? $hari }}" readonly
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
    @else
        {{-- Mode create: pilih hari --}}
        <select name="hari" id="hari" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">-- Pilih Hari --</option>
            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                <option value="{{ $day }}" @selected(old('hari', $hari ?? '') == $day)>
                    {{ $day }}
                </option>
            @endforeach
        </select>
    @endif
</div>

<div class="mb-4">
    <label for="jam_mapel_id" class="block text-sm font-medium text-gray-700">Jam Mapel</label>

    @if ($jamMapel ?? false)
        {{-- Jika jamMapel dikirim (createWithJam / edit) --}}
        <input type="text"
            value="{{ $jamMapel->nomor_jam }} ({{ $jamMapel->jam_mulai }} - {{ $jamMapel->jam_selesai }})" readonly
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
        <input type="hidden" name="jam_mapel_id" value="{{ $jamMapel->id }}">
    @else
        {{-- Jika create biasa: user pilih jam_mapel --}}
        <select name="jam_mapel_id" id="jam_mapel_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">-- Pilih Jam Mapel --</option>
            @foreach ($jadwal->shift->jamMapels()->orderBy('nomor_jam')->get() as $jm)
                <option value="{{ $jm->id }}" @selected(old('jam_mapel_id') == $jm->id)>
                    {{ $jm->nomor_jam }} ({{ $jm->jam_mulai }} - {{ $jm->jam_selesai }})
                </option>
            @endforeach
        </select>
    @endif
</div>

<div class="mb-4">
    <label for="mapel_id" class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
    <select name="mapel_id" id="mapel_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        <option value="">-- Pilih Mata Pelajaran --</option>
        @foreach ($mapels as $mapel)
            <option value="{{ $mapel->id }}" @selected(old('mapel_id', $detail->mapel_id ?? '') == $mapel->id)>
                {{ $mapel->nama_mapel }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label for="guru_id" class="block text-sm font-medium text-gray-700">Guru</label>
    <select name="guru_id" id="guru_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        <option value="">-- Pilih Guru --</option>
        @foreach ($gurus as $guru)
            <option value="{{ $guru->id }}" @selected(old('guru_id', $detail->guru_id ?? '') == $guru->id)>
                {{ $guru->nama_guru }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <button type="submit"
        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">{{ $tombol }}</button>
</div>
