<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal {{ $namaKelas }} <span class="text-gray-500"> Shift {{ $jadwal->shift->nama }}</span>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

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
                                                
                                            @else
                                                <div class="flex justify-center mt-3">
                                                    -
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
                                            
                                        @else
                                            <div class="flex justify-center">
                                                -
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
