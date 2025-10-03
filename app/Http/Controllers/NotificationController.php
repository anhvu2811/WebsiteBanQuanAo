<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function getUserNotifications(Request $request) 
    {
        $data = Notification::where('user_id', 2)->get();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function maskAsRead($id)
    {
        $data = Notification::find($id);
        if(!$data) {
            return;
        }
        $data->update(['is_read' => 1]);
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
