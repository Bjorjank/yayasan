<?php

namespace App\Services\Payments\Contracts;

use App\Models\Donation;

interface PaymentGateway
{
    /**
     * Membuat "transaksi" pembayaran dan mengembalikan data instruksi yang akan ditunjukkan ke user.
     * Return example:
     * [
     *   'payment_ref' => 'SIM-20251111-000123',
     *   'payment_method' => 'virtual_account',
     *   'instructions' => 'Transfer ke VA 1234567890 a.n Yayasan Demo',
     *   'expires_at' => now()->addMinutes(60),
     * ]
     */
    public function create(Donation $donation): array;

    /**
     * Menandai sebagai LUNAS (settlement) — dipanggil setelah user konfirmasi "sudah bayar".
     */
    public function settle(Donation $donation): Donation;

    /**
     * Batalkan (cancel) transaksi.
     */
    public function cancel(Donation $donation): Donation;

    /**
     * Tandai kadaluarsa (expire) jika melewati waktu berlaku.
     */
    public function expire(Donation $donation): Donation;
}
