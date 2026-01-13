<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
            Scan QR untuk Absensi
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-2xl p-6 text-center space-y-6">

                {{-- Notifikasi --}}
                @if (session('success'))
                    <div class="p-3 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100 rounded-lg text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('info'))
                    <div class="p-3 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100 rounded-lg text-sm font-medium">
                        {{ session('info') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="p-3 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100 rounded-lg text-sm font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">
                        Arahkan kamera ke QR Code
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Pastikan pencahayaan cukup dan QR terlihat jelas.
                    </p>
                </div>

                <div id="reader"
                     class="mx-auto rounded-2xl border border-gray-200 dark:border-gray-700 shadow-inner overflow-hidden"
                     style="max-width: 360px; width: 100%; aspect-ratio:4/3;">
                </div>

                <div id="result"
                     class="text-center text-sm text-gray-700 dark:text-gray-300 font-medium">
                </div>

                <div class="text-xs text-gray-400 dark:text-gray-500">
                    Kamera tidak muncul? Izinkan akses kamera di browser Anda.
                </div>
            </div>
        </div>
    </div>

    {{-- Script Scanner --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const html5QrCode = new Html5Qrcode("reader");
            const resultBox = document.getElementById('result');

            function onScanSuccess(decodedText) {
                resultBox.innerText = "QR terbaca: " + decodedText;
                setTimeout(() => {
                    window.location.href = "/absen?token=" + encodeURIComponent(decodedText);
                }, 500);
            }

            Html5Qrcode.getCameras().then(devices => {
                if (devices.length) {
                    html5QrCode.start(
                        devices[0].id,
                        { fps: 10, qrbox: { width: 250, height: 250 } },
                        onScanSuccess
                    );
                } else {
                    resultBox.innerText = "Kamera tidak terdeteksi.";
                }
            }).catch(err => {
                resultBox.innerText = "Gagal mengakses kamera: " + err;
            });
        });
    </script>
</x-app-layout>
