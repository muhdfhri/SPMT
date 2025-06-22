<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Menampilkan daftar notifikasi
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Jika request hanya meminta jumlah notifikasi yang belum dibaca
        if ($request->header('X-Only-Count')) {
            return response()->json([
                'unread_count' => $user->unreadNotifications()->count()
            ]);
        }
        
        // Jika request AJAX (API)
        if ($request->ajax() || $request->wantsJson()) {
            $notifications = $user->notifications()
                ->latest()
                ->take(10)
                ->get()
                ->map(function($notification) {
                    $data = $notification->data;
                    return [
                        'id' => $notification->id,
                        'title' => $data['title'] ?? 'Notifikasi',
                        'message' => $data['message'] ?? '',
                        'type' => $data['type'] ?? 'info',
                        'created_at' => $notification->created_at->diffForHumans(),
                        'read_at' => $notification->read_at,
                        'url' => $data['action_url'] ?? '#',
                    ];
                });
                
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $user->unreadNotifications()->count(),
            ]);
        }
        
        // Jika request biasa (halaman web)
        $notifications()
            ->latest()
            ->paginate(15);
            
        return view('notifications.index', compact('notifications'));
    }
    
    /**
     * Menandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        if (!$notification->read_at) {
            $notification->markAsRead();
        }
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi telah ditandai sebagai sudah dibaca'
            ]);
        }
        
        return redirect()->back()->with('success', 'Notifikasi telah ditandai sebagai sudah dibaca');
    }
    
    /**
     * Menandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        $count = Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi telah ditandai sebagai sudah dibaca',
                'count' => $count
            ]);
        }
        
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca');
    }
    
    /**
     * Menghapus notifikasi
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $this->authorize('delete', $notification);
        
        $notification->delete();
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil dihapus'
            ]);
        }
        
        return back()->with('success', 'Notifikasi berhasil dihapus');
    }
    
    /**
     * Mendapatkan jumlah notifikasi yang belum dibaca (API)
     */
    public function unreadCount()
    {
        $count = Auth::user()
            ->notifications()
            ->whereNull('read_at')
            ->count();
            
        return response()->json([
            'success' => true,
            'unread_count' => $count
        ]);
    }
    
    /**
     * Mendapatkan daftar notifikasi (API)
     */
    public function apiIndex(Request $request)
    {
        $user = Auth::user();
        
        // Hitung jumlah notifikasi yang belum dibaca
        $unreadCount = $user->unreadNotifications()->count();
        
        // Ambil daftar notifikasi terbaru
        $notifications = $user->notifications()
            ->select('id', 'title', 'message', 'type', 'action_url', 'read_at', 'created_at')
            ->latest()
            ->take(10)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type ?? 'info',
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'url' => $notification->action_url ?? '#',
                ];
            });
            
        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca (API)
     */
    public function apiMarkAsRead(Notification $notification)
    {
        $this->authorize('markAsRead', $notification);
        
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi telah ditandai sebagai sudah dibaca'
        ]);
    }
    
    /**
     * Menandai semua notifikasi sebagai sudah dibaca (API)
     */
    public function apiMarkAllAsRead()
    {
        Auth::user()->unreadNotifications->each->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi telah ditandai sebagai sudah dibaca'
        ]);
    }
}