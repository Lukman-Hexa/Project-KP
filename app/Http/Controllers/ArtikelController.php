<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    public function index()
    {
        return view('artikel');
    }
    
    /**
     * Mengambil semua data artikel dari database.
     * Digunakan untuk API.
     */
    public function all()
    {
        $artikels = Artikel::all();
        return response()->json($artikels);
    }
    
    /**
     * Mengambil satu artikel berdasarkan ID untuk diedit.
     */
    public function show($id)
    {
        $artikel = Artikel::findOrFail($id);
        return response()->json($artikel);
    }
     public function store(Request $request)
    {
        $request->validate([
            'judul_artikel' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar_artikel' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Simpan file langsung di folder public/artikel
        $imagePath = $request->file('gambar_artikel')->store('artikel', 'public');
        
        $artikel = Artikel::create([
            'judul_artikel' => $request->judul_artikel,
            'deskripsi' => $request->deskripsi,
            'gambar_artikel' => $imagePath,
        ]);

        return response()->json($artikel);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_artikel' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar_artikel' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $artikel = Artikel::findOrFail($id);
        $artikel->judul_artikel = $request->judul_artikel;
        $artikel->deskripsi = $request->deskripsi;

        if ($request->hasFile('gambar_artikel')) {
            // Hapus gambar lama
            Storage::disk('public')->delete($artikel->gambar_artikel);
            $imagePath = $request->file('gambar_artikel')->store('artikel', 'public');
            $artikel->gambar_artikel = $imagePath;
        }

        $artikel->save();

        return response()->json($artikel);
    }
    
    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);
        Storage::disk('public')->delete($artikel->gambar_artikel);
        $artikel->delete();
        
        return response()->json(null, 204);
    }
}