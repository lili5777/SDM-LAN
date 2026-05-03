<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\Folder;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenPegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Auth::user()->pegawai;

        // Ambil SEMUA folder aktif (bukan hanya yang ada dokumennya)
        $allFolders = Folder::with('kategoriFolder')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        // Ambil dokumen pegawai yang aktif
        $dokumenList = [];
        if ($pegawai) {
            $dokumenList = Dokumen::with(['folder', 'folder.kategoriFolder'])
                ->where('pegawai_id', $pegawai->id)
                ->where('status', 'aktif')
                ->latest()
                ->get()
                ->keyBy('id');
        }

        // Bangun struktur: kategori -> folder -> [dokumen]
        // Semua folder tampil, dokumen bisa kosong []
        $struktur = [];
        foreach ($allFolders as $folder) {
            $katNama = $folder->kategoriFolder->nama ?? 'Lainnya';
            $folderNama = $folder->nama;

            if (!isset($struktur[$katNama])) {
                $struktur[$katNama] = [];
            }
            if (!isset($struktur[$katNama][$folderNama])) {
                $struktur[$katNama][$folderNama] = [
                    'folder'   => $folder,
                    'dokumens' => [],
                ];
            }
        }

        // Isi dokumen ke folder yang sesuai
        foreach ($dokumenList as $dok) {
            $katNama    = $dok->folder->kategoriFolder->nama ?? 'Lainnya';
            $folderNama = $dok->folder->nama ?? 'Lainnya';

            if (isset($struktur[$katNama][$folderNama])) {
                $struktur[$katNama][$folderNama]['dokumens'][] = $dok;
            }
        }

        return view('pegawai.dokumen.index', compact('struktur', 'dokumenList'));
    }

    public function create()
    {
        $folders = Folder::with('kategoriFolder')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get()
            ->groupBy('kategoriFolder.nama');

        return view('pegawai.dokumen.create', compact('folders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'folder_id'       => 'required|exists:folder,id,is_active,1',
            'judul'           => 'required|string|max:255',
            'nomor_dokumen'   => 'nullable|string|max:100',
            'tanggal_dokumen' => 'nullable|date',
            'file'            => 'required|file|max:10240',
            'keterangan'      => 'nullable|string',
        ]);

        $folder = Folder::findOrFail($request->folder_id);
        $file   = $request->file('file');

        // Validasi ekstensi
        $extension = $file->getClientOriginalExtension();
        if ($folder->ekstensi_allowed && !in_array(strtolower($extension), $folder->ekstensi_allowed)) {
            return back()->with('error', 'Ekstensi file tidak diizinkan. Yang diizinkan: ' . implode(', ', $folder->ekstensi_allowed));
        }

        // Validasi ukuran
        $maxSizeBytes = $folder->max_size_mb * 1024 * 1024;
        if ($file->getSize() > $maxSizeBytes) {
            return back()->with('error', "Ukuran file maksimal {$folder->max_size_mb} MB");
        }

        $pegawai = Auth::user()->pegawai;

        if (!$pegawai) {
            return back()->with('error', 'Data pegawai tidak ditemukan');
        }

        // Simpan file sementara
        $tempPath = $file->store('temp/' . date('Y/m/d'), 'public');

        // Buat pengajuan
        $pengajuan = Pengajuan::create([
            'pegawai_id'      => $pegawai->id,
            'jenis'           => 'upload',
            'folder_id'       => $request->folder_id,
            'judul'           => $request->judul,
            'nomor_dokumen'   => $request->nomor_dokumen,
            'tanggal_dokumen' => $request->tanggal_dokumen,
            'file_path'       => $tempPath,
            'file_name'       => $file->getClientOriginalName(),
            'file_size'       => $file->getSize(),
            'file_type'       => $file->getMimeType(),
            'keterangan'      => $request->keterangan,
            'status'          => 'menunggu',
        ]);

        // Kirim notifikasi ke admin
        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->get();

        foreach ($admins as $admin) {
            $admin->sendNotification(
                'Pengajuan Upload Baru',
                "Pegawai {$pegawai->nama} mengajukan upload dokumen '{$request->judul}'",
                'info',
                route('pengajuan.show', $pengajuan->id)
            );
        }

        return redirect()->route('pengajuan.status')
            ->with('success', 'Pengajuan upload berhasil dikirim');
    }

    public function ajukanHapus(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|min:5',
        ]);

        $dokumen = Dokumen::findOrFail($id);
        $pegawai = Auth::user()->pegawai;

        if ($dokumen->pegawai_id !== $pegawai->id) {
            abort(403);
        }

        // Cek pengajuan yang masih pending
        $existing = Pengajuan::where('dokumen_id', $id)
            ->where('jenis', 'hapus')
            ->where('status', 'menunggu')
            ->exists();

        if ($existing) {
            return back()->with('error', 'Sudah ada pengajuan hapus yang masih menunggu');
        }

        $pengajuan = Pengajuan::create([
            'pegawai_id'       => $pegawai->id,
            'jenis'            => 'hapus',
            'dokumen_id'       => $id,
            'folder_id'        => $dokumen->folder_id,
            'judul'            => $dokumen->judul,
            'nomor_dokumen'    => $dokumen->nomor_dokumen,
            'tanggal_dokumen'  => $dokumen->tanggal_dokumen,
            'alasan_pengajuan' => $request->alasan,
            'status'           => 'menunggu',
        ]);

        // Kirim notifikasi ke admin
        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->get();

        foreach ($admins as $admin) {
            $admin->sendNotification(
                'Pengajuan Hapus Baru',
                "Pegawai {$pegawai->nama} mengajukan hapus dokumen '{$dokumen->judul}'",
                'peringatan',
                route('pengajuan.show', $pengajuan->id)
            );
        }

        return redirect()->route('pengajuan.status')
            ->with('success', 'Pengajuan hapus berhasil dikirim');
    }
}