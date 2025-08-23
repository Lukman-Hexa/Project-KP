<?php

namespace App\Http\Controllers;

use App\Models\Artikel; // Import model Artikel
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        // Mengambil semua artikel dari database
        $artikels = Artikel::all();
        // Mengirim data artikel ke tampilan homepage
        return view('public.homepage', compact('artikels'));
    }
}