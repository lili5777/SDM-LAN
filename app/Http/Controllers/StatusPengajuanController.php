<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StatusPengajuanController extends Controller
{
    public function index(Request $request)
    {
        $pegawai = Auth::user()->pegawai;
        
        if (!$pegawai) {
            return view('pegawai.pengajuan.index', ['pengajuans' => collect([])]);
        }
        
        $query = Pengajuan::with(['folder', 'folder.kategoriFolder'])
            ->where('pegawai_id', $pegawai->id);
        
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $pengajuans = $query->latest()->paginate(15);
        
        return view('pegawai.pengajuan.index', compact('pengajuans'));
    }
    
    public function show($id)
    {
        $pegawai = Auth::user()->pegawai;
        
        $pengajuan = Pengajuan::with(['folder', 'folder.kategoriFolder', 'reviewer'])
            ->where('pegawai_id', $pegawai->id)
            ->findOrFail($id);
        
        return view('pegawai.pengajuan.show', compact('pengajuan'));
    }
    
    public function batalkan($id)
    {
        $pegawai = Auth::user()->pegawai;
        
        $pengajuan = Pengajuan::where('pegawai_id', $pegawai->id)
            ->where('status', 'menunggu')
            ->findOrFail($id);
        
        if ($pengajuan->jenis === 'upload' && $pengajuan->file_path) {
            Storage::disk('public')->delete($pengajuan->file_path);
        }
        
        $pengajuan->delete();
        
        return redirect()->route('pengajuan.status')->with('success', 'Pengajuan berhasil dibatalkan');
    }
}