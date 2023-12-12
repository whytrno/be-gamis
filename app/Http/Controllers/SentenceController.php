<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Sentence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SentenceController extends Controller
{
    //tampilkan semua data
    public function index($count = null)
    {
        if ($count) {
            $sentences = Sentence::inRandomOrder()->get($count);
        } else {
            $sentences = Sentence::all();
        }
        //kembalikan dalam bentuk json
        return $this->successResponse($sentences);
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


    public function answer(Request $request)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.id_question' => 'required|integer',

        ]);

        $userAnswer = $request->questions;

        foreach ($userAnswer as $ua) {
            $user = Auth::user();
            //history
            $history = History::create([
                'user_id' => $user->id,
                'game_type' => 'sentence',
                'game_id' => $ua['id_question'],
            ]);
            return $this->successResponse($history, 'History created successfully', 201);
            //end history

            $user->score += 15;
            $user->save();
        }
        return $this->successResponse('Correct Answer', [
            'score' => $user->score,
        ]);
    }
}
