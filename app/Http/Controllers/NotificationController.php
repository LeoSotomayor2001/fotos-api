<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notifications(Request $request)
    {
        $user = $request->user();
        $notifications = $user->unreadNotifications()->paginate(10);

        return response()->json($notifications);
    }

    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();
        $notification = $user->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notificación marcada como leída']);
        }

        return response()->json(['message' => 'Notificación no encontrada'], 404);
    }
    public function markAllAsRead(Request $request)
    {
        $user = auth(guard: 'api')->user();
        $user->unreadNotifications->markAsRead();

        return response()->json(['message' => 'Todas las notificaciones marcadas como leídas']);
    }
}
