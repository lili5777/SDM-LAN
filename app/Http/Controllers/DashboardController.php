<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Dokumen;
use App\Models\Pengajuan;
use App\Models\Pegawai;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role->name === 'admin') {
            return $this->adminDashboard();
        }
        
        return $this->pegawaiDashboard();
    }
    
    private function adminDashboard()
    {
        $data = [
            'total_dokumen' => Dokumen::count(),
            'total_pegawai' => Pegawai::count(),
            'pending_pengajuan' => Pengajuan::where('status', 'menunggu')->count(),
            'pending_upload' => Pengajuan::where('jenis', 'upload')->where('status', 'menunggu')->count(),
            'pending_hapus' => Pengajuan::where('jenis', 'hapus')->where('status', 'menunggu')->count(),
            'pengajuan_terbaru' => Pengajuan::with(['pegawai', 'folder'])
                ->where('status', 'menunggu')
                ->latest()
                ->take(5)
                ->get(),
        ];
        
        return view('dashboard.admin', $data);
    }
    
    private function pegawaiDashboard()
    {
        $pegawai = Auth::user()->pegawai;
        
        if (!$pegawai) {
            $data = [
                'total_dokumen' => 0,
                'pending_pengajuan' => 0,
                'disetujui_count' => 0,
                'ditolak_count' => 0,
                'recent_pengajuan' => collect([]),
            ];
        } else {
            $data = [
                'total_dokumen' => Dokumen::where('pegawai_id', $pegawai->id)->where('status', 'aktif')->count(),
                'pending_pengajuan' => Pengajuan::where('pegawai_id', $pegawai->id)->where('status', 'menunggu')->count(),
                'disetujui_count' => Pengajuan::where('pegawai_id', $pegawai->id)->where('status', 'disetujui')->count(),
                'ditolak_count' => Pengajuan::where('pegawai_id', $pegawai->id)->where('status', 'ditolak')->count(),
                'recent_pengajuan' => Pengajuan::where('pegawai_id', $pegawai->id)->latest()->take(5)->get(),
                'pegawai' => $pegawai,
            ];
        }
        
        return view('dashboard.pegawai', $data);
    }
}