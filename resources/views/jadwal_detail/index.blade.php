<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal Detail - {{ $jadwal->kelas->nama_kelas }} ({{ $shift->nama }})
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <table class="w-full border text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-2 py-1 border">Hari</th>
                            @foreach ($jamMapels as $jam)
                                <th class="px-2 py-1 border text-center">
                                    {{ $jam->nomor_jam }}<br>
                                    <span class="text-xs text-gray-500">
                                        {{ $jam->jam_mulai }} - {{ $jam->jam_selesai }}
                                    </span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                            <tr>
                                <td class="px-2 py-1 border text-center font-medium">{{ $hari }}</td>

                                @php
                                    // Ambil detail jam_mapel untuk hari ini, urutkan slot
                                    $dayDetails = data_get($details, $hari, collect())
                                        ->sortBy(fn($d) => $d->jamMapel->nomor_jam)
                                        ->values();
                                    $totalSlot = $jamMapels->count();
                                    $i = 0;
                                @endphp

                                @while ($i < $totalSlot)
                                    @php
                                        $currentJam = $jamMapels[$i];
                                        $detail = $dayDetails->firstWhere('jam_mapel_id', $currentJam->id);

                                        // Default colspan = 1
                                        $colspan = 1;
                                        $j = $i + 1;

                                        if ($detail) {
                                            // Cek ke depan, gabungkan kalau mapel+guru sama
                                            while (
                                                $j < $totalSlot &&
                                                ($nextDetail = $dayDetails->firstWhere('jam_mapel_id', $jamMapels[$j]->id)) &&
                                                $nextDetail->mapel_id == $detail->mapel_id &&
                                                $nextDetail->guru_id == $detail->guru_id
                                            ) {
                                                $colspan++;
                                                $j++;
                                            }
                                        }
                                    @endphp

                                    @if ($detail)
                                        <td colspan="{{ $colspan }}" class="px-2 py-1 border text-center bg-green-50">
                                            <strong>{{ $detail->mapel->nama_mapel }}</strong><br>
                                            <span class="text-xs text-gray-600">{{ $detail->guru->nama_guru }}</span>
                                            <div class="mt-1 flex justify-center space-x-2 text-xs">
                                                <a href="{{ route('guru-piket.jadwal.details.edit', [$jadwal->id, $detail->id]) }}"
                                                    class="text-blue-600 hover:underline">Edit</a>
                                                <form action="{{ route('guru-piket.jadwal.details.destroy', [$jadwal->id, $detail->id]) }}"
                                                    method="POST" onsubmit="return confirm('Hapus detail ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    @else
                                        <td class="px-2 py-1 border text-center bg-gray-50">
                                            <a href="{{ route('guru-piket.jadwal.details.create', ['jadwal' => $jadwal->id, 'hari' => $hari, 'jam_mapel_id' => $currentJam->id]) }}"
                                                class="text-green-600 hover:underline text-xs">+ Tambah</a>
                                        </td>
                                    @endif

                                    @php $i = $j; @endphp
                                @endwhile
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
