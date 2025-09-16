@csrf
<div class="mb-4">
    <label class="block text-sm font-medium">Nama Shift</label>
    <input type="text" name="nama" placeholder="Contoh: Shift Pagi (07.00 - 11.30)"
           value="{{ old('nama', $shift->nama ?? '') }}"
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    @error('nama') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<button type="submit"
        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
    {{ $tombol }}
</button>
