<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

final class CustomAuthController extends Controller
{
    public function auth(Request $request)
    {
        $userId = 1;
        $user = User::where('id', $userId)->first();

        if (!$user) {
            // Jika token tidak valid, kembalikan 401 Unauthorized
            return response()->json(['message' => 'Invalid token.', 'desc' => 'User '. $userId . ' tidak ditemukan.'], 401);
        }

        // harus auth login, biar bisa Broadcast::auth();
        Auth::login($user);

        // Lakukan otorisasi menggunakan logika di routes/channels.php
        $authResponse = Broadcast::auth($request);

        return response()->json($authResponse, 200);
    }
}