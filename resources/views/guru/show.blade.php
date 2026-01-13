<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Detail Guru') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 sm:p-8">
                <div class="space-y-6">

                    {{-- Nama Guru --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400">Nama Guru</label>
                        <p class="mt-1 text-base font-semibold text-slate-800 dark:text-slate-100">
                            {{ $guru->nama_guru }}
                        </p>
                    </div>

                    {{-- NIP --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400">NIP</label>
                        <p class="mt-1 text-base font-semibold text-slate-800 dark:text-slate-100">
                            {{ $guru->nip }}
                        </p>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400">Email</label>
                        <p class="mt-1 text-base font-semibold text-slate-800 dark:text-slate-100">
                            {{ $guru->user->email }}
                        </p>
                    </div>

                    {{-- Tanggal Bergabung --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400">Tanggal Bergabung</label>
                        <p class="mt-1 text-base font-semibold text-slate-800 dark:text-slate-100">
                            {{ $guru->created_at->format('d-m-Y') }}
                        </p>
                    </div>

                    {{-- Mata Pelajaran (Opsional) --}}
                    {{-- 
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400">
                            Mata Pelajaran yang Diajar
                        </label>
                        @if($guru->mapels->isEmpty())
                            <p class="mt-1 text-slate-500 dark:text-slate-400">
                                Tidak ada mata pelajaran yang diajar.
                            </p>
                        @else
                            <ul class="mt-2 list-disc list-inside text-slate-700 dark:text-slate-300 space-y-1">
                                @foreach($guru->mapels as $mapel)
                                    <li>{{ $mapel->nama_mapel }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    --}}

                </div>

                {{-- Tombol Kembali --}}
                <div class="mt-8 flex justify-end">
                    <a href="{{ route('guru-piket.guru.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-lg shadow transition-all duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
