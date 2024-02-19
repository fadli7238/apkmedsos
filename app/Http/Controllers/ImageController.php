<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feeds = Image::latest()->simplepaginate(2);
        return view('image.index', compact('feeds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('image.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'file', 'mimes:png,jpg,jpeg', 'max:2000'],
            'caption' => ['required', 'max:100'],
        ]);

        $image = new Image();
        $image->created_by = $request->created_by;
        $image->image = $request->file('image')->store('feeds');
        $image->caption = $request->caption;
        $image->save();

        return redirect()->route('image.index')->with('success', 'image Berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        // Hapus image dari penyimpanan sebelum menghapus data dari database
        if ($image->image) {
            Storage::delete($image->image);
        }

        if ($image->delete()) {
            return redirect()->route('image.index')->with('success', 'image berhasil dihapus!');
        }

        return redirect()->route('image.index')->with('error', 'Gagal menghapus image.');
    }

    
}
