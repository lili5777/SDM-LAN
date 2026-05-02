<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriFolder;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    // ========== KATEGORI FOLDER ==========
    
    public function indexKategori()
    {
        $kategoris = KategoriFolder::with('folders')->orderBy('urutan')->orderBy('nama')->get();
        return view('admin.folder.kategori.index', compact('kategoris'));
    }
    
    public function createKategori()
    {
        return view('admin.folder.kategori.create');
    }
    
    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        
        KategoriFolder::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'urutan' => $request->urutan ?? 0,
            'is_active' => $request->has('is_active'),
            'created_by' => Auth::id(),
        ]);
        
        return redirect()->route('folder.kategori.index')->with('success', 'Kategori folder berhasil dibuat');
    }
    
    public function editKategori($id)
    {
        $kategori = KategoriFolder::findOrFail($id);
        return view('admin.folder.kategori.edit', compact('kategori'));
    }
    
    public function updateKategori(Request $request, $id)
    {
        $kategori = KategoriFolder::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        
        $kategori->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'urutan' => $request->urutan ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()->route('folder.kategori.index')->with('success', 'Kategori folder berhasil diupdate');
    }
    
    public function destroyKategori($id)
    {
        $kategori = KategoriFolder::findOrFail($id);
        
        if ($kategori->folders()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki folder');
        }
        
        $kategori->delete();
        return redirect()->route('folder.kategori.index')->with('success', 'Kategori folder berhasil dihapus');
    }
    
    // ========== FOLDER ==========
    
    public function indexFolder()
    {
        $kategoris = KategoriFolder::with(['folders' => function($q) {
            $q->orderBy('urutan')->orderBy('nama');
        }])->orderBy('urutan')->get();
        
        return view('admin.folder.index', compact('kategoris'));
    }
    
    public function createFolder()
    {
        $kategoris = KategoriFolder::where('is_active', true)->orderBy('urutan')->get();
        return view('admin.folder.create', compact('kategoris'));
    }
    
    public function storeFolder(Request $request)
    {
        $request->validate([
            'kategori_folder_id' => 'required|exists:kategori_folder,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'ekstensi_allowed' => 'nullable|array',
            'max_size_mb' => 'nullable|integer|min:1|max:100',
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        
        Folder::create([
            'kategori_folder_id' => $request->kategori_folder_id,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'ekstensi_allowed' => $request->ekstensi_allowed,
            'max_size_mb' => $request->max_size_mb ?? 10,
            'urutan' => $request->urutan ?? 0,
            'is_active' => $request->has('is_active'),
            'created_by' => Auth::id(),
        ]);
        
        return redirect()->route('folder.index')->with('success', 'Folder berhasil dibuat');
    }
    
    public function editFolder($id)
    {
        $folder = Folder::findOrFail($id);
        $kategoris = KategoriFolder::where('is_active', true)->orderBy('urutan')->get();
        return view('admin.folder.edit', compact('folder', 'kategoris'));
    }
    
    public function updateFolder(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);
        
        $request->validate([
            'kategori_folder_id' => 'required|exists:kategori_folder,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'ekstensi_allowed' => 'nullable|array',
            'max_size_mb' => 'nullable|integer|min:1|max:100',
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        
        $folder->update([
            'kategori_folder_id' => $request->kategori_folder_id,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'ekstensi_allowed' => $request->ekstensi_allowed,
            'max_size_mb' => $request->max_size_mb ?? 10,
            'urutan' => $request->urutan ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()->route('folder.index')->with('success', 'Folder berhasil diupdate');
    }
    
    public function destroyFolder($id)
    {
        $folder = Folder::findOrFail($id);
        
        if ($folder->dokumens()->count() > 0) {
            return back()->with('error', 'Folder tidak bisa dihapus karena masih memiliki dokumen');
        }
        
        $folder->delete();
        return redirect()->route('folder.index')->with('success', 'Folder berhasil dihapus');
    }
    
    public function toggleFolder($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->is_active = !$folder->is_active;
        $folder->save();
        
        $status = $folder->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Folder berhasil {$status}");
    }
}