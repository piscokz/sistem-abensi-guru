@csrf
<div class="mb-4">
    <label class="block text-sm font-medium">Nomor Jam</label>
    <input type="number" name="nomor_jam"
           value="{{ old('nomor_jam', $jamMapel->nomor_jam ?? '') }}"
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    @error('nomor_jam') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium">Jam Mulai</label>
    <input type="time" name="jam_mulai"
           value="{{ old('jam_mulai', isset($jamMapel->jam_mulai) ? \Carbon\Carbon::parse($jamMapel->jam_mulai)->format('H:i') : '') }}"
           step="60"
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    @error('jam_mulai') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium">Jam Selesai</label>
    <input type="time" name="jam_selesai"
           value="{{ old('jam_selesai', isset($jamMapel->jam_selesai) ? \Carbon\Carbon::parse($jamMapel->jam_selesai)->format('H:i') : '') }}"
           step="60"
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    @error('jam_selesai') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium">Keterangan (opsional)</label>
    <input type="text" name="keterangan"
           value="{{ old('keterangan', $jamMapel->keterangan ?? '') }}"
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    @error('keterangan') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<button type="submit"
        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
    {{ $tombol }}
</button>
