<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal Guru {{ $nama_guru }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto space-y-8 px-4 sm:px-6 lg:px-8">
            @forelse ($groupedByShift as $data)
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden transition hover:shadow-xl">
                    <div class="bg-gradient-to-r from-indigo-500 to-blue-500 text-white px-5 py-3">
                        <h3 class="text-lg font-semibold tracking-wide">
                            Shift: {{ $data['shift']->nama }}
                        </h3>
                    </div>

                    <!-- Desktop View -->
                    <div class="hidden md:block divide-y divide-gray-200">
                        @foreach ($data['details']->groupBy('hari') as $hari => $items)
                            <div class="p-5">
                                <h4 class="text-md font-semibold text-gray-700 mb-3 border-b pb-2">
                                    {{ ucfirst($hari) }}
                                </h4>

                                <table class="w-full border-collapse">
                                    <thead class="bg-gray-100">
                                        <tr class="text-left text-gray-700">
                                            <th class="px-4 py-3 font-semibold border-b border-gray-200 w-1/4">Jam</th>
                                            <th class="px-4 py-3 font-semibold border-b border-gray-200">Kelas - Mapel</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $detail)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-4 py-3 border-b border-gray-200">
                                                    {{ $detail->jamMapel->jam_mulai }} - {{ $detail->jamMapel->jam_selesai }}
                                                </td>
                                                <td class="px-4 py-3 border-b border-gray-200">
                                                    {{ $detail->jadwal->kelas->nama_kelas }} - {{ $detail->mapel->nama_mapel }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>

                    <!-- Mobile View -->
                    <div class="md:hidden divide-y divide-gray-200">
                        @foreach ($data['details']->groupBy('hari') as $hari => $items)
                            <div class="p-4">
                                <h4 class="text-base font-semibold text-gray-700 mb-2">
                                    {{ ucfirst($hari) }}
                                </h4>
                                <div class="space-y-3">
                                    @foreach ($items as $detail)
                                        <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
                                            <p class="text-sm text-gray-500 font-medium">
                                                {{ $detail->jamMapel->jam_mulai }} - {{ $detail->jamMapel->jam_selesai }}
                                            </p>
                                            <p class="text-gray-800 font-semibold text-base">
                                                {{ $detail->jadwal->kelas->nama_kelas }}
                                            </p>
                                            <p class="text-gray-700">
                                                {{ $detail->mapel->nama_mapel }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center italic py-8">Tidak ada jadwal untuk guru ini.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
