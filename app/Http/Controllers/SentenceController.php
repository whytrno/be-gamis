<?php

namespace App\Http\Controllers;

use App\Models\Sentence;
use Illuminate\Http\Request;

class SentenceController extends Controller
{
    //tampilkan semua data
    public function index()
    {
        //ambil semua data database
        $sentences = Sentence::all();
        //kembalikan dalam bentuk json
        return response()->json(['sentences' => $sentences]);
    }
    //tampilkan data berdasarkan id
    public function show($id)
    {
        //cari data berdasarkan id
        $sentence = Sentence::findOrFail($id);
        //kembalikan dalam bentuk json
        return response()->json(['sentence' => $sentence]);
    }
    //tambah data
    public function store(Request $request)
    {
        //validasi data
        $request->validate([
            'question' => 'required',
        ]);
        //tambah data
        $sentence = Sentence::create($request->all());
        //kembalikan dalam bentuk json dengan kode status 201 (created)
        return response()->json(['sentence' => $sentence], 201);
    }
    //ubah data
    public function update(Request $request, $id)
    {
        //validasi data
        $request->validate([
            'question' => 'required',
        ]);
        //cari data berdasarkan id
        $sentence = Sentence::findOrFail($id);
        //ubah data
        $sentence->update($request->all());
        //kembalikan dalam bentuk json
        return response()->json(['sentence' => $sentence]);
    }
    //hapus data
    public function destroy($id)
    {
        //cari data berdasarkan id
        $sentence = Sentence::findOrFail($id);
        //hapus data
        $sentence->delete();
        //kembalikan dalam bentuk json dengan pesan "Sentence deleted successfully"
        return response()->json(['message' => 'Sentence deleted successfully']);
    }
}
