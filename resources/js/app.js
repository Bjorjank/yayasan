// resources/js/app.js

// Pastikan file ini dipanggil oleh @vite([...,'resources/js/app.js'])
import "./bootstrap.js";

// === Alpine.js ===
import Alpine from "alpinejs";

// Log “smoke test” — WAJIB terlihat di Console setelah reload halaman
console.log("[app.js] Alpine starting…");
window.Alpine = Alpine;

// (opsional) Global flag debug yang bisa kamu baca di widget
window.__CHAT_DEBUG__ = true;

// Start Alpine
Alpine.start();
console.log("[app.js] Alpine started.");

// Tambahan: saat halaman ready, pastikan elemen widget ada
window.addEventListener("DOMContentLoaded", () => {
    const el = document.getElementById("chat-widget-root");
    console.log("[app.js] DOMContentLoaded. chat-widget-root =", !!el);
});
