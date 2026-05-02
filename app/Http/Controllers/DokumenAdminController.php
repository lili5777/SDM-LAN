<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\Pegawai;
use App\Models\Folder;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DokumenAdminController extends Controller
{
    /**
     * Pastikan ekstensi_allowed selalu jadi array,
     * apapun bentuk yang tersimpan di database (JSON string atau sudah array).
     */
    private function parseEkstensi($raw): array
    {
        if (is_array($raw)) {
            return $raw;
        }
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : ['pdf', 'jpg', 'png'];
    }

    // =========================================================================
    // INDEX — Layar 1: pilih pegawai (grid pegawai)
    // =========================================================================
    public function index(Request $request)
    {
        $query = Pegawai::with('unitKerja')
            ->where('status', 'aktif')
            ->withCount(['dokumens' => function ($q) {
                $q->where('status', 'aktif');
            }])
            ->orderBy('nama');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('nip',  'like', "%{$request->search}%");
            });
        }

        $pegawais = $query->get();

        // Stats global
        $totalPegawai = Pegawai::where('status', 'aktif')->count();
        $totalDokumen = Dokumen::count();
        $totalAktif   = Dokumen::where('status', 'aktif')->count();
        $total30Hari  = Dokumen::where('created_at', '>=', now()->subDays(30))->count();

        return view('admin.dokumen.index', compact(
            'pegawais',
            'totalPegawai',
            'totalDokumen',
            'totalAktif',
            'total30Hari'
        ));
    }

    // =========================================================================
    // PEGAWAI VIEW — Layar 2: grid folder milik pegawai tertentu
    // =========================================================================
    public function pegawaiView($pegawaiId)
    {
        $pegawai = Pegawai::with('unitKerja')->findOrFail($pegawaiId);

        // Ambil semua folder aktif, hitung dokumen milik pegawai ini per folder
        $folders = Folder::with('kategoriFolder')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get()
            ->each(function ($folder) use ($pegawaiId) {
                $folder->dokumens_by_pegawai_count = Dokumen::where('folder_id', $folder->id)
                    ->where('pegawai_id', $pegawaiId)
                    ->where('status', 'aktif')
                    ->count();
            });

        // Kelompokkan per kategori
        $foldersByKategori = $folders->groupBy(fn($f) => $f->kategoriFolder->nama ?? 'Tanpa Kategori');

        // Total dokumen pegawai ini
        $totalDokumenPegawai = Dokumen::where('pegawai_id', $pegawaiId)
            ->where('status', 'aktif')
            ->count();

        return view('admin.dokumen.pegawai', compact(
            'pegawai',
            'foldersByKategori',
            'totalDokumenPegawai'
        ));
    }

    // =========================================================================
    // FOLDER VIEW — Layar 3: list file dalam folder milik pegawai
    // =========================================================================
    public function folderView(Request $request, $pegawaiId, $folderId)
    {
        $pegawai = Pegawai::with('unitKerja')->findOrFail($pegawaiId);
        $folder  = Folder::with('kategoriFolder')->findOrFail($folderId);

        $query = Dokumen::with(['uploader', 'approver'])
            ->where('pegawai_id', $pegawaiId)
            ->where('folder_id',  $folderId);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul',          'like', "%{$request->search}%")
                  ->orWhere('nomor_dokumen', 'like', "%{$request->search}%");
            });
        }

        $dokumens = $query->latest()->paginate(20);

        return view('admin.dokumen.folder', compact('pegawai', 'folder', 'dokumens'));
    }

    // =========================================================================
    // CREATE — form upload admin
    // =========================================================================
    public function create(Request $request)
    {
        $pegawais = Pegawai::with('unitKerja')
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $folders = Folder::with('kategoriFolder')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get()
            ->each(function ($folder) {
                $folder->ekstensi_json = json_encode(
                    $this->parseEkstensi($folder->ekstensi_allowed)
                );
            })
            ->groupBy('kategoriFolder.nama');

        // Pre-select dari query string (jika datang dari tombol "Upload ke Folder Ini")
        $selectedPegawaiId = $request->query('pegawai_id');
        $selectedFolderId  = $request->query('folder_id');

        return view('admin.dokumen.create', compact(
            'pegawais',
            'folders',
            'selectedPegawaiId',
            'selectedFolderId'
        ));
    }

    // =========================================================================
    // STORE — simpan dokumen langsung aktif
    // =========================================================================
    public function store(Request $request)
    {
        $folder = Folder::findOrFail($request->folder_id);

        $allowedExt = $this->parseEkstensi($folder->ekstensi_allowed);
        $maxSizeMb  = $folder->max_size_mb ?? 10;
        $extRule    = 'mimes:' . implode(',', $allowedExt);
        $sizeRule   = 'max:'  . ($maxSizeMb * 1024);

        $request->validate([
            'pegawai_id'      => 'required|exists:pegawai,id',
            'folder_id'       => 'required|exists:folder,id',
            'judul'           => 'required|string|max:255',
            'nomor_dokumen'   => 'nullable|string|max:100',
            'tanggal_dokumen' => 'required|date',
            'keterangan'      => 'nullable|string|max:1000',
            'file'            => ['required', 'file', $extRule, $sizeRule],
        ], [
            'file.mimes' => 'Format file tidak didukung. Format yang diizinkan: ' . implode(', ', $allowedExt),
            'file.max'   => "Ukuran file maksimal {$maxSizeMb} MB.",
        ]);

        $file         = $request->file('file');
        $pegawaiId    = $request->pegawai_id;
        $originalName = $file->getClientOriginalName();
        $safeName     = time()
                      . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME))
                      . '.' . $file->getClientOriginalExtension();
        $filePath     = $file->storeAs(
            "dokumen/{$pegawaiId}/{$folder->id}",
            $safeName,
            'public'
        );

        $dokumen = Dokumen::create([
            'pegawai_id'      => $pegawaiId,
            'folder_id'       => $folder->id,
            'judul'           => $request->judul,
            'nomor_dokumen'   => $request->nomor_dokumen,
            'tanggal_dokumen' => $request->tanggal_dokumen,
            'file_path'       => $filePath,
            'file_name'       => $originalName,
            'file_size'       => $file->getSize(),
            'file_type'       => $file->getMimeType(),
            'keterangan'      => $request->keterangan,
            'status'          => 'aktif',
            'uploaded_by'     => Auth::id(),
            'approved_by'     => Auth::id(),
            'approved_at'     => now(),
        ]);

        // Notifikasi ke pegawai jika punya akun login
        $pegawai = $dokumen->pegawai()->with('user')->first();
        if ($pegawai && $pegawai->user) {
            Notifikasi::create([
                'user_id' => $pegawai->user->id,
                'judul'   => 'Dokumen Baru Ditambahkan',
                'pesan'   => "Admin menambahkan dokumen \"{$dokumen->judul}\" ke folder {$folder->nama} milik Anda.",
                'tipe'    => 'info',
                'is_read' => false,
                'url'     => route('dokumen.saya'),
            ]);
        }

        // Redirect kembali ke folder view setelah upload
        return redirect()
            ->route('admin.dokumen.folder', [
                'pegawai' => $pegawaiId,
                'folder'  => $folder->id,
            ])
            ->with('success', "Dokumen \"{$dokumen->judul}\" berhasil diunggah.");
    }

    // =========================================================================
    // SHOW
    // =========================================================================
    public function show($id)
    {
        $dokumen = Dokumen::with([
            'pegawai',
            'pegawai.unitKerja',
            'folder',
            'folder.kategoriFolder',
            'uploader',
            'approver',
        ])->findOrFail($id);

        return view('admin.dokumen.show', compact('dokumen'));
    }

    // =========================================================================
    // DOWNLOAD
    // =========================================================================
    public function download($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        if (Storage::disk('public')->exists($dokumen->file_path)) {
            return Storage::disk('public')->download($dokumen->file_path, $dokumen->file_name);
        }

        return back()->with('error', 'File tidak ditemukan');
    }

    // =========================================================================
    // PREVIEW
    // =========================================================================
    public function preview($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        if (Storage::disk('public')->exists($dokumen->file_path)) {
            return response(
                Storage::disk('public')->get($dokumen->file_path),
                200
            )->header('Content-Type', $dokumen->file_type);
        }

        return back()->with('error', 'File tidak ditemukan');
    }
}