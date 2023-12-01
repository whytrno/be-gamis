<?php

// app/Http/Controllers/MultipleChoiceController.php
namespace App\Http\Controllers;

use App\Models\MultipleChoice;
use Illuminate\Http\Request;

class MultipleChoiceController extends Controller
{
    //tampilkan semua data
    public function index()
    {
        //ambil semua data database
        $questions = MultipleChoice::all();
        //kembalikan dalam bentuk json
        return response()->json(['questions' => $questions]);
    }

    //tampilkan data berdasarkan id
    public function show($id)
    {
        //cari data berdasarkan id
        $question = MultipleChoice::findOrFail($id);
        //kembalikan dalam bentuk json
        return response()->json(['question' => $question]);
    }

    //tambah data
    public function store(Request $request)
    {
        //validasi data
        $request->validate([
            'question' => 'required',
        ]);
        //tambah data
        $question = MultipleChoice::create($request->all());
        //kembalikan dalam bentuk json dengan kode status 201 (created)
        return response()->json(['question' => $question], 201);
    }
    //ubah data
    public function update(Request $request, $id)
    {
        //validasi data
        $request->validate([
            'question' => 'required',
        ]);
        //cari data berdasarkan id
        $question = MultipleChoice::findOrFail($id);
        //ubah data
        $question->update($request->all());
        //kembalikan dalam bentuk json
        return response()->json(['question' => $question]);
    }
    //hapus data
    public function destroy($id)
    {
        //cari data berdasarkan id
        $question = MultipleChoice::findOrFail($id);
        //hapus data
        $question->delete();
        //kembalikan dalam bentuk json dengan pesan string "Question deleted successfully"
        return response()->json(['message' => 'Question deleted successfully']);
    }
}
