<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    //tampil semua data
    public function index()
    {
        //ambil semua data database
        $articles = Article::all();
        //kembalikan dalam bentuk json
        return response()->json(['articles' => $articles]);
    }
    //tampilkan data berdasarkan id
    public function show($id)
    {
        //cari data berdasarkan id
        $article = Article::findOrFail($id);
        //kembalikan dalam bentuk json
        return response()->json(['article' => $article]);
    }
    //tambah data
    public function store(Request $request)
    {
        //validasi data
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        //tambah data
        $article = Article::create($request->all());
        //kembalikan dalam bentuk json dengan kode status 201 (created)
        return response()->json(['article' => $article], 201);
    }
    //ubah data
    public function update(Request $request, $id)
    {
        //validasi data
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        //cari data berdasarkan id
        $article = Article::findOrFail($id);
        //ubah data
        $article->update($request->all());
        //kembalikan dalam bentuk json
        return response()->json(['article' => $article]);
    }
    //hapus data
    public function destroy($id)
    {
        //cari data berdasarkan id
        $article = Article::findOrFail($id);
        //hapus data
        $article->delete();
        //kembalikan dalam bentuk json dengan pesan "Article deleted successfully"
        return response()->json(['message' => 'Article deleted successfully']);
    }
}
