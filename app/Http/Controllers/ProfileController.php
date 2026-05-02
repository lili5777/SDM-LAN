<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;
        
        return view('profile.index', compact('user', 'pegawai'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        
        $user->update([
            'name' => $request->name,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
        ]);
        
        return back()->with('success', 'Profile berhasil diupdate');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah');
        }
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return back()->with('success', 'Password berhasil diubah');
    }
    
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|max:2048',
        ]);
        
        $pegawai = Auth::user()->pegawai;
        
        if ($pegawai) {
            if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            
            $path = $request->file('foto')->store('foto_pegawai', 'public');
            $pegawai->update(['foto' => $path]);
        }
        
        return back()->with('success', 'Foto profile berhasil diupdate');
    }
}