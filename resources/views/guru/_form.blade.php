@csrf
<div class="space-y-6">

    {{-- Nama Guru --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Guru</label>
        <input type="text" id="name" name="name" value="{{ old('name', $guru->nama_guru ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror"
            required>
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- NIP --}}
    <div>
        <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
        <input type="text" id="nip" name="nip" value="{{ old('nip', $guru->nip ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nip') border-red-500 @enderror"
            required>
        @error('nip')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $guru->user->email ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('email') border-red-500 @enderror"
            required>
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Mapel --}}
    {{-- <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Mapel yang Diajar</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
            @foreach ($mapels as $mapel)
                <label class="inline-flex items-center space-x-2">
                    <input type="checkbox" name="mapels[]" value="{{ $mapel->id }}"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        @if (isset($guruMapelIds) && in_array($mapel->id, $guruMapelIds)) checked @endif>
                    <span class="text-sm text-gray-700">{{ $mapel->nama_mapel }}</span>
                </label>
            @endforeach
        </div>
        @error('mapels')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div> --}}


    {{-- Password --}}
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" id="password" name="password"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('password') border-red-500 @enderror"
            @if (!isset($guru)) required @endif>
        @if (isset($guru))
            <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
        @endif
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Konfirmasi Password --}}
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            @if (!isset($guru)) required @endif>
    </div>

    <div>
        <button type="submit"
            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            {{ $tombol }}
        </button>
    </div>

</div>
