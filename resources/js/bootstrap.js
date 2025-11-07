// resources/js/bootstrap.js

/**
 * Bootstrap untuk Axios (opsional tapi aman) + Laravel Echo (Reverb)
 * Kita sengaja tetap set X-Requested-With agar endpoint web paham ini AJAX.
 */
import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// ==== Laravel Echo (Reverb) ====
// Penting: pastikan env Vite sudah diset: VITE_REVERB_APP_KEY, VITE_REVERB_HOST, VITE_REVERB_PORT
// Kalau kamu belum butuh realtime, biarkan tetap di sini—widget akan tetap jalan pakai fetch.
import Echo from "laravel-echo";

try {
    window.Echo = new Echo({
        broadcaster: "reverb",
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
        wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
        forceTLS: false,
        enabledTransports: ["ws", "wss"],
    });
    // Debug ringan agar tahu Echo terpasang
    console.log("[bootstrap.js] Echo initialized");
} catch (e) {
    console.warn("[bootstrap.js] Echo init failed:", e?.message || e);
}
