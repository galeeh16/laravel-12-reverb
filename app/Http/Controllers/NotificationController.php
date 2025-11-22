<?php

namespace App\Http\Controllers;

use App\Models\Notification;

use Illuminate\Http\Request;
use App\Events\NotificationCreated;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'title' => 'required|string',
            'message' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid Data', 'data' => $validator->getMessageBag()], 422);
        }
        
        // // 1. Simpan ke DB
        Notification::insert([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // // 2. Broadcast ke frontend real-time
        broadcast(new NotificationCreated(
            $request->user_id,
            $request->title,
            $request->message,
        ));

        return response()->json(['message' => 'Success send broadcast.'], Response::HTTP_OK);
    }

    public function markAsRead($id)
    {
        $notif = Notification::query()->where('id', $id)->first();
        if (!$notif) {
            return response()->json(['message' => 'Notif ' . $id . ' tidak ditemukan.'], 404);
        }
        
        $notif->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function list(Request $request)
    {
        $list = Notification::orderBy('id', 'DESC')->get();

        return response()->json(['message' => 'Success', 'data' => $list], 200);
    }
}

