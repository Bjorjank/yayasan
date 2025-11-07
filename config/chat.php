<?php

return [
    // Room chat default untuk widget (mis. room grup umum)
    'default_room_id' => (int) env('CHAT_DEFAULT_ROOM_ID', 1),
    // Jumlah pesan awal yang dimuat
    'initial_take' => (int) env('CHAT_INITIAL_TAKE', 50),
];
