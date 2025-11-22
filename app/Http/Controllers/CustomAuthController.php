<?php 

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthController extends Controller
{
    public function auth(Request $request)
    {
        $user = User::where('id', 1)->first();
        if (!$user) {
            // Jika token tidak valid, kembalikan 401 Unauthorized
            return response()->json(['message' => 'Invalid token.'], Response::HTTP_UNAUTHORIZED);
        }

        // harus auth login, biar bisa Broadcast::auth();
        Auth::loginUsingId(1);

        // Lakukan otorisasi menggunakan logika di routes/channels.php
        $authResponse = Broadcast::auth($request);

        return response()->json($authResponse, Response::HTTP_OK);
    }
}