<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::with('unitKerja', 'user')->latest()->paginate(15);
        return view('admin.pegawai.index', compact('pegawais'));
    }
    
    public function create()
    {
        $unitKerjas = UnitKerja::all();
        return view('admin.pegawai.create', compact('unitKerjas'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:pegawai',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);
        
        Pegawai::create($request->all());
        
        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $unitKerjas = UnitKerja::all();
        return view('admin.pegawai.edit', compact('pegawai', 'unitKerjas'));
    }
    
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        
        $request->validate([
            'nip' => 'required|unique:pegawai,nip,' . $id,
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);
        
        $pegawai->update($request->all());
        
        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diupdate');
    }
    
    public function createUser($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        
        if ($pegawai->user_id) {
            return back()->with('error', 'Pegawai sudah memiliki akun');
        }
        
        return view('admin.pegawai.create-user', compact('pegawai'));
    }
    
    public function storeUser(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $role = Role::where('name', 'pegawai')->first();
        
        $user = User::create([
            'name' => $pegawai->nama,
            'email' => $request->email,
            'no_telp' => $pegawai->no_hp,
            'role_id' => $role->id,
            'password' => Hash::make($request->password),
        ]);
        
        $pegawai->update(['user_id' => $user->id]);
        
        return redirect()->route('pegawai.index')->with('success', 'Akun berhasil dibuat untuk pegawai');
    }
    
    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        
        if ($pegawai->dokumens()->count() > 0) {
            return back()->with('error', 'Pegawai tidak bisa dihapus karena masih memiliki dokumen');
        }
        
        $pegawai->delete();
        
        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil dihapus');
    }
}