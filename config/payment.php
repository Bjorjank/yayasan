<?php

return [
    // Driver aktif: 'fake' (custom kita). Nanti bisa tambah 'midtrans' dll.
    'driver' => env('PAYMENTS_DRIVER', 'fake'),

    // Konfigurasi khusus per driver
    'drivers' => [
        'fake' => [
            // berapa menit sebelum dianggap kadaluarsa (simulasi)
            'expire_minutes' => 60,
            // prefix kode pembayaran simulasi
            'code_prefix' => 'SIM',
        ],
        // contoh placeholder midtrans (nanti diisi)
        'midtrans' => [
            'server_key'    => env('MIDTRANS_SERVER_KEY'),
            'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),
        ],
    ],
];
