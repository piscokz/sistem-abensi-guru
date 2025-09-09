@csrf
<div class="mb-4">
    <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
    <input type="text" id="nama_kelas" name="nama_kelas"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
           value="{{ old('nama_kelas', $kelas->nama_kelas ?? '') }}" required>
    @error('nama_kelas')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="email" class="block text-sm font-medium text-gray-700">Email Akun</label>
    <input type="email" id="email" name="email"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
           value="{{ old('email', $kelas->user->email ?? '') }}" required>
    @error('email')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
    <input type="password" id="password" name="password"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
           @if(!isset($kelas->id)) required @endif>
    @if(isset($kelas->id))
        <p class="text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
    @endif
    @error('password')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
    <input type="password" id="password_confirmation" name="password_confirmation"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
           @if(!isset($kelas->id)) required @endif>
</div>

<button type="submit"
        class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
    {{ $tombol }}
</button>
