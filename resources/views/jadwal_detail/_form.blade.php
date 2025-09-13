@csrf

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Hari</label>
    <select name="hari" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @foreach($hariList as $h)
            <option value="{{ $h }}" 
                @selected(old('hari', $detail->hari ?? $defaultHari ?? '') == $h)>
                {{ $h }}
            </option>
        @endforeach
    </select>
    @error('hari') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Slot</label>
    <select name="slot_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @foreach($slots as $slot)
            <option value="{{ $slot->id }}" 
                @selected(old('slot_id', $detail->slot_id ?? $defaultSlot ?? '') == $slot->id)>
                {{ $slot->nomor_slot }} ({{ $slot->jam_mulai }} - {{ $slot->jam_selesai }})
            </option>
        @endforeach
    </select>
    @error('slot_id') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Mapel</label>
    <select name="mapel_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @foreach($mapels as $mapel)
            <option value="{{ $mapel->id }}" 
                @selected(old('mapel_id', $detail->mapel_id ?? '') == $mapel->id)>
                {{ $mapel->nama_mapel }}
            </option>
        @endforeach
    </select>
    @error('mapel_id') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Guru</label>
    <select name="guru_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @foreach($gurus as $guru)
            <option value="{{ $guru->id }}" 
                @selected(old('guru_id', $detail->guru_id ?? '') == $guru->id)>
                {{ $guru->nama }}
            </option>
        @endforeach
    </select>
    @error('guru_id') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
</div>

<button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
    {{ $tombol }}
</button>
