<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitKerja;

class UnitKerjaController extends Controller
{
    public function index()
    {
        $unitKerjas = UnitKerja::withCount('pegawais')->latest()->get();
        return view('admin.unit-kerja.index', compact('unitKerjas'));
    }
    
    public function create()
    {
        return view('admin.unit-kerja.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:unit_kerja',
            'nama' => 'required',
        ]);
        
        UnitKerja::create($request->all());
        
        return redirect()->route('unit-kerja.index')->with('success', 'Unit kerja berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $unitKerja = UnitKerja::findOrFail($id);
        return view('admin.unit-kerja.edit', compact('unitKerja'));
    }
    
    public function update(Request $request, $id)
    {
        $unitKerja = UnitKerja::findOrFail($id);
        
        $request->validate([
            'kode' => 'required|unique:unit_kerja,kode,' . $id,
            'nama' => 'required',
        ]);
        
        $unitKerja->update($request->all());
        
        return redirect()->route('unit-kerja.index')->with('success', 'Unit kerja berhasil diupdate');
    }
    
    public function destroy($id)
    {
        $unitKerja = UnitKerja::findOrFail($id);
        
        if ($unitKerja->pegawais()->count() > 0) {
            return back()->with('error', 'Unit kerja tidak bisa dihapus karena masih memiliki pegawai');
        }
        
        $unitKerja->delete();
        
        return redirect()->route('unit-kerja.index')->with('success', 'Unit kerja berhasil dihapus');
    }
}