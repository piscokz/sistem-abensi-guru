<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal {{ $namaKelas }} <span class="text-gray-500"> Shift {{ $jadwal->shift->nama }}</span>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                {{-- tambah jadwal detail --}}
                <a href="{{ route('guru-piket.jadwal.details.create', $jadwal) }}"
                    class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Mapel
                </a>


                {{-- Versi Tabel (desktop / tablet) --}}
                <div class="overflow-x-auto hidden sm:block">
                    <table class="min-w-full border border-gray-300 divide-y divide-gray-200 text-center text-sm">
                        <thead class="bg-gray-100 sticky top-0">
                            <tr>
                                <th class="px-4 py-2 border border-gray-300 font-semibold text-gray-700">Hari</th>
                                @foreach ($jamMapel as $jam)
                                    <th class="px-4 py-2 border border-gray-300 font-semibold text-gray-700">
                                        {{ $jam->nomor_jam }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwalDetail as $hari => $listHari)
                                <tr class="hover:bg-gray-50">
                                    <td
                                        class="px-4 py-2 border border-gray-300 font-medium text-gray-800 whitespace-nowrap">
                                        {{ $hari }}
                                    </td>
                                    @foreach ($jamMapel as $jam)
                                        @php
                                            $data = $listHari->firstWhere('jam_mapel_id', $jam->id);
                                        @endphp
                                        <td class="px-4 py-2 border border-gray-300 align-top">
                                            @if ($data)
                                                <div class="text-gray-800 font-medium">
                                                    {{ $data->mapel->nama_mapel }}
                                                </div>
                                                <div class="text-xs text-gray-600 mb-2">
                                                    ({{ $data->guru->nama_guru }})
                                                </div>
                                                <div class="flex justify-center gap-2">
                                                    <form
                                                        action="{{ route('guru-piket.jadwal.details.edit', [$jadwal->id, $data->id]) }}"
                                                        method="GET">
                                                        @csrf
                                                        <button type="submit"
                                                            class="px-2 py-1 text-xs bg-blue-100 text-blue-600 rounded hover:bg-blue-200">Edit</button>
                                                    </form>
                                                    <form
                                                        action="{{ route('guru-piket.jadwal.details.destroy', [$jadwal->id, $data->id]) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded hover:bg-red-200">Hapus</button>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="flex justify-center mt-3">
                                                    <form
                                                        action="{{ route('guru-piket.jadwal.details.createWithJam', [$jadwal->id, $jam->id, $hari]) }}"
                                                        method="GET">
                                                        @csrf
                                                        <input type="hidden" name="jadwal_id"
                                                            value="{{ $jadwal->id }}">
                                                        <input type="hidden" name="hari"
                                                            value="{{ $hari }}">
                                                        <input type="hidden" name="jam_mapel_id"
                                                            value="{{ $jam->id }}">
                                                        <button type="submit"
                                                            class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600">+</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Versi Card (mobile) --}}
                <div class="block sm:hidden space-y-4">
                    @foreach ($jadwalDetail as $hari => $listHari)
                        <div class="bg-white shadow rounded-lg border border-gray-200 p-4">
                            <h3 class="font-semibold text-gray-800 mb-3">{{ $hari }}</h3>
                            <div class="space-y-3">
                                @foreach ($jamMapel as $jam)
                                    @php
                                        $data = $listHari->firstWhere('jam_mapel_id', $jam->id);
                                    @endphp
                                    <div class="border border-gray-200 rounded p-2">
                                        <div class="text-xs text-gray-500 mb-1">Jam {{ $jam->nomor_jam }}</div>
                                        @if ($data)
                                            <div class="text-gray-800 font-medium">{{ $data->mapel->nama_mapel }}</div>
                                            <div class="text-xs text-gray-600 mb-2">({{ $data->guru->nama_guru }})
                                            </div>
                                            <div class="flex gap-2">
                                                <form
                                                    action="{{ route('guru-piket.jadwal.details.edit', [$jadwal->id, $data->id]) }}"
                                                    method="GET">
                                                    @csrf
                                                    <button type="submit"
                                                        class="px-2 py-1 text-xs bg-blue-100 text-blue-600 rounded hover:bg-blue-200">Edit</button>
                                                </form>
                                                <form
                                                    action="{{ route('guru-piket.jadwal.details.destroy', [$jadwal->id, $data->id]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded hover:bg-red-200">Hapus</button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="flex justify-center">
                                                <form
                                                    action="{{ route('guru-piket.jadwal.details.createWithJam', [$jadwal->id, $jam->id, $hari]) }}"
                                                    method="GET">
                                                    @csrf
                                                    <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                                                    <input type="hidden" name="hari" value="{{ $hari }}">
                                                    <input type="hidden" name="jam_mapel_id"
                                                        value="{{ $jam->id }}">
                                                    <button type="submit"
                                                        class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600">+</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
