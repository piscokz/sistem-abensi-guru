<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal Detail - {{ $jadwal->kelas->nama_kelas }} ({{ $jadwal->nama_jadwal }})
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('guru-piket.jadwal.details.create', $jadwal->id) }}">Tambah Detail</a>

                <table class="w-full border-collapse border">
                    <thead>
                        <tr>
                            <th class="px-2 py-1 border text-center">Hari</th>
                            @foreach ($jamMapels as $jam)
                                <th class="px-2 py-1 border text-center">{{ $jam->nama_jam ?? $jam->nomor_jam }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                            @if (!empty($groupedDetails[$hari]))
                                <tr>
                                    <td class="px-2 py-1 border text-center font-medium">{{ $hari }}</td>
                                    @foreach ($groupedDetails[$hari] as $slot)
                                        @if ($slot['type'] === 'detail')
                                            @dd()
                                            <td colspan="{{ $slot['colspan'] }}"
                                                class="px-2 py-1 border text-center bg-green-50">
                                                <strong>{{ $slot['detail']->jadwalDetail->mapel->nama_mapel }}</strong><br>
                                                <span class="text-xs text-gray-600">
                                                    {{ $slot['detail']->jadwalDetail->guru->nama_guru }}
                                                </span>
                                                <div class="mt-1 flex justify-center space-x-2 text-xs">
                                                    <a href="{{ route('guru-piket.jadwal.details.edit', [$jadwal->id, $slot['detail']->id]) }}"
                                                        class="text-blue-600 hover:underline">Edit</a>
                                                    <form
                                                        action="{{ route('guru-piket.jadwal.details.destroy', [$jadwal->id, $slot['detail']->id]) }}"
                                                        method="POST" onsubmit="return confirm('Hapus detail ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:underline">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        @else
                                            <td colspan="{{ $slot['colspan'] }}"
                                                class="px-2 py-1 border text-center bg-gray-50">
                                                <a href="{{ route('guru-piket.jadwal.details.createWithJam', [
                                                    'jadwal' => $jadwal->id,
                                                    'jam_mapel' => $slot['jam_id'],
                                                    'hari' => $hari,
                                                ]) }}"
                                                    class="text-green-600 hover:underline text-xs">+ Tambah</a>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>


            </div>
        </div>
    </div>
</x-app-layout>
