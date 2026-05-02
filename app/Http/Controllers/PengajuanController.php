<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Dokumen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    public function pending()
    {
        $pengajuans = Pengajuan::with(['pegawai', 'folder', 'dokumen'])
            ->where('status', 'menunggu')
            ->latest()
            ->paginate(20);
        
        return view('admin.pengajuan.pending', compact('pengajuans'));
    }
    
    public function history(Request $request)
    {
        $query = Pengajuan::with(['pegawai', 'folder', 'reviewer']);
        
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', "%{$request->search}%")
                  ->orWhereHas('pegawai', function($q2) use ($request) {
                      $q2->where('nama', 'like', "%{$request->search}%");
                  });
            });
        }
        
        $pengajuans = $query->latest()->paginate(20);
        
        return view('admin.pengajuan.history', compact('pengajuans'));
    }
    
    public function show($id)
    {
        $pengajuan = Pengajuan::with(['pegawai', 'folder', 'folder.kategoriFolder', 'dokumen'])
            ->findOrFail($id);
        
        return view('admin.pengajuan.show', compact('pengajuan'));
    }
    
    public function approve(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        if ($pengajuan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan sudah diproses');
        }
        
        if ($pengajuan->jenis === 'upload') {
            // Pindahkan file
            $oldPath = $pengajuan->file_path;
            $newPath = str_replace('temp/', 'dokumen/', $oldPath);
            Storage::disk('public')->move($oldPath, $newPath);
            
            // Buat dokumen
            Dokumen::create([
                'pegawai_id' => $pengajuan->pegawai_id,
                'folder_id' => $pengajuan->folder_id,
                'judul' => $pengajuan->judul,
                'nomor_dokumen' => $pengajuan->nomor_dokumen,
                'tanggal_dokumen' => $pengajuan->tanggal_dokumen,
                'file_path' => $newPath,
                'file_name' => $pengajuan->file_name,
                'file_size' => $pengajuan->file_size,
                'file_type' => $pengajuan->file_type,
                'keterangan' => $pengajuan->keterangan,
                'uploaded_by' => $pengajuan->pegawai->user_id,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
        } else {
            // Hapus dokumen
            $dokumen = $pengajuan->dokumen;
            if ($dokumen && Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            if ($dokumen) {
                $dokumen->update(['status' => 'diarsipkan']);
            }
        }
        
        $pengajuan->update([
            'status' => 'disetujui',
            'catatan_admin' => $request->catatan,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);
        
        // Notifikasi ke pegawai
        $pegawaiUser = $pengajuan->pegawai->user;
        if ($pegawaiUser) {
            $pegawaiUser->sendNotification(
                'Pengajuan Disetujui',
                "Pengajuan '{$pengajuan->judul}' telah disetujui",
                'sukses',
                route('pengajuan.status')
            );
        }
        
        return redirect()->route('pengajuan.pending')->with('success', 'Pengajuan disetujui');
    }
    
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|min:5',
        ]);
        
        $pengajuan = Pengajuan::findOrFail($id);
        
        if ($pengajuan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan sudah diproses');
        }
        
        if ($pengajuan->jenis === 'upload' && $pengajuan->file_path) {
            Storage::disk('public')->delete($pengajuan->file_path);
        }
        
        $pengajuan->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->alasan,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);
        
        // Notifikasi ke pegawai
        $pegawaiUser = $pengajuan->pegawai->user;
        if ($pegawaiUser) {
            $pegawaiUser->sendNotification(
                'Pengajuan Ditolak',
                "Pengajuan '{$pengajuan->judul}' ditolak. Alasan: {$request->alasan}",
                'error',
                route('pengajuan.status')
            );
        }
        
        return redirect()->route('pengajuan.pending')->with('success', 'Pengajuan ditolak');
    }
}