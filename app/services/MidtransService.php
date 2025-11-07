<?php
// app/Services/MidtransService.php
namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService {
    public function __construct() {
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$clientKey    = config('services.midtrans.client_key');
    }
    public function createSnap(array $params): array {
        $snapToken = Snap::getSnapToken($params);
        return ['token' => $snapToken, 'redirect_url' => "https://app.sandbox.midtrans.com/snap/v3/redirection/{$snapToken}"];
    }
}
