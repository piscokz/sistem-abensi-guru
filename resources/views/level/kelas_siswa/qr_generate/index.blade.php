@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Absensi dengan QR</h2>
    <p>Nama: {{ $siswa->nama_siswa }}</p>

    <div class="alert alert-info">
        Fitur QR akan ditambahkan di tahap selanjutnya.
    </div>
</div>
@endsection
