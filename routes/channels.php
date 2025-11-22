<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.notifications.{userId}', function ($user, $userId) {
    return true; // Izinkan semua user autentikasi
},);
