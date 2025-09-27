@csrf
<div class="mb-4">
    <label for="shift_id" class="block text-sm font-medium text-gray-700">Pilih Shift</label>
    <select name="shift_id" id="shift_id"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        required>
        <option value="">-- Pilih Shift --</option>
        @foreach ($shifts as $shift)
            <option value="{{ $shift->id }}"
                {{ old('shift_id', isset($jadwal) ? $jadwal->shift_id : '') == $shift->id ? 'selected' : '' }}>
                {{ $shift->nama }}
            </option>
        @endforeach
    </select>
    @error('shift_id')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <button type="submit"
        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none">
        {{ $tombol }}
    </button>
</div>
