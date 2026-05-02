<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Auth::user()->notifikasis()->latest()->paginate(20);
        
        Auth::user()->notifikasis()->where('is_read', false)->update(['is_read' => true]);
        
        return view('notifikasi.index', compact('notifikasis'));
    }
    
    public function unreadCount()
    {
        $count = Auth::user()->notifikasis()->where('is_read', false)->count();
        return response()->json(['count' => $count]);
    }
    
    public function markAsRead($id)
    {
        $notifikasi = Notifikasi::where('user_id', Auth::id())->findOrFail($id);
        $notifikasi->is_read = true;
        $notifikasi->save();
        
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead()
    {
        Auth::user()->notifikasis()->where('is_read', false)->update(['is_read' => true]);
        
        return redirect()->route('notifikasi.index')->with('success', 'Semua notifikasi telah ditandai dibaca');
    }
}