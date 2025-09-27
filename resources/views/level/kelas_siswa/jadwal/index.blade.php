{{-- resources/views/level/kelas/jadwal/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal Kelas {{ $kelas->nama ?? '' }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($message)
                <div class="mb-4 rounded-md bg-yellow-100 p-4 text-yellow-800">
                    {{ $message }}
                </div>
            @endif

            @if ($jadwal && $details->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="min-w-full border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 border">Hari</th>
                                    <th class="px-4 py-2 border">Jam</th>
                                    <th class="px-4 py-2 border">Mapel</th>
                                    <th class="px-4 py-2 border">Guru</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details as $hari => $list)
                                    @foreach ($list as $item)
                                        <tr>
                                            <td class="px-4 py-2 border">{{ $hari }}</td>
                                            <td class="px-4 py-2 border">
                                                {{ $item->jamMapel->jam_mulai }} - {{ $item->jamMapel->jam_selesai }}
                                            </td>
                                            <td class="px-4 py-2 border">{{ $item->mapel->nama }}</td>
                                            <td class="px-4 py-2 border">{{ $item->guru->nama }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
