<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Jadwal Detail - {{ $jadwal->kelas->nama_kelas }} ({{ $jadwal->tahun_ajaran }})
            {{-- @dd($jadwal->kelas->nama_kelas) --}}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-4 overflow-x-auto">

            @if(session('success'))
                <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded">{{ session('success') }}</div>
            @endif

            <table class="min-w-full border text-xs sm:text-sm text-center">
                <thead>
                    <tr>
                        <th class="border px-2 py-1">Jam</th>
                        @foreach ($hariList as $hari)
                            <th class="border px-2 py-1">{{ $hari }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($slots as $slot)
                        <tr>
                            <td class="border px-2 py-2">
                                {{ $slot->nomor_slot }}<br>
                                <span class="text-gray-500 text-xs">{{ $slot->jam_mulai }} - {{ $slot->jam_selesai }}</span>
                            </td>
                            @foreach ($hariList as $hari)
                                @php
                                    $detail = $jadwal->details
                                        ->where('slot_id', $slot->id)
                                        ->where('hari', $hari)
                                        ->first();
                                @endphp
                                <td class="border px-2 py-2 align-top">
                                    @if ($detail)
                                        <div class="font-medium">{{ $detail->mapel->nama_mapel }}</div>
                                        <div class="text-xs text-gray-600">{{ $detail->guru->nama }}</div>
                                        <div class="mt-1 flex justify-center space-x-2 text-xs">
                                            <a href="{{ route('guru-piket.jadwal_detail.edit', $detail->id) }}" class="text-indigo-600">Edit</a>
                                            <form action="{{ route('guru-piket.jadwal_detail.destroy', $detail->id) }}" method="POST" onsubmit="return confirm('Hapus detail ini?');">
                                                @csrf @method('DELETE')
                                                <button class="text-red-600">Hapus</button>
                                            </form>
                                        </div>
                                    @else
                                        <a href="{{ route('guru-piket.jadwal_detail.create', ['jadwal'=>$jadwal->id,'slot'=>$slot->id,'hari'=>$hari]) }}" class="text-green-600 text-xs">+ Tambah</a>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</x-app-layout>
