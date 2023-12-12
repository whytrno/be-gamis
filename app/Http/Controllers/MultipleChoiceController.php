<?php

// app/Http/Controllers/MultipleChoiceController.php
namespace App\Http\Controllers;

use App\Models\History;
use App\Models\MultipleChoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultipleChoiceController extends Controller
{

    //tampilkan semua data
    public function index($count = null)
    {
        if ($count) {
            $questions = MultipleChoice::inRandomOrder()->get($count);
        } else {
            $questions = MultipleChoice::all();
        }

        //kembalikan dalam bentuk json
        return $this->successResponse($questions);
    }

    //tampilkan data berdasarkan id
    public function show($id)
    {
        //cari data berdasarkan id
        $question = MultipleChoice::findOrFail($id);
        //kembalikan dalam bentuk json
        return $this->successResponse($question);
    }

    //koreksi
    //tambah data
    public function store(Request $request)
    {
        //validasi data
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            'correct_answer' => 'required',
        ]);
        //tambah data
        $question = MultipleChoice::create($request->all());
        //kembalikan dalam bentuk json dengan kode status 201 (created)
        return $this->successResponse($question, 'Question created successfully', 201);
    }
    //ubah data
    public function update(Request $request, $id)
    {
        //validasi data
        $request->validate([
            'answer' => 'nullable',
            'question' => 'nullable',
            'correct_answer' => 'nullable',
        ]);
        //cari data berdasarkan id
        $question = MultipleChoice::findOrFail($id);
        //ubah data
        $question->update($request->all());
        //kembalikan dalam bentuk json
        return $this->successResponse($question, 'Question updated successfully');
    }
    //hapus data
    public function destroy($id)
    {
        //cari data berdasarkan id
        $question = MultipleChoice::findOrFail($id);
        //hapus data
        $question->delete();
        //kembalikan dalam bentuk json dengan pesan string "Question deleted successfully"
        return $this->successResponse('Question deleted successfully');
    }

    public function answer(Request $request)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.id_question' => 'required|integer',
            'questions.*.answer' => 'required|integer',
        ]);

        $userAnswer = $request->questions;
        $correctAnswer = 0;
        $wrongAnswers = 0;


        foreach ($userAnswer as $ua) {
            $question = MultipleChoice::find($ua['id_question']);

            $checkAnswer = $question->correct_answer == $ua['answer'] ? true : false;
            $user = Auth::user();
            //history
            $history = History::create([
                'user_id' => $user->id,
                'game_type' => 'multiple_choice',
                'game_id' => $ua['id_question'],
            ]);

            return $this->successResponse($history, 'History created successfully', 201);
            //end history
            if ($checkAnswer) {
                $correctAnswer++;
                $user->score += 15;
                $user->save();
            } else {
                $wrongAnswers++;
            }
        }
        return $this->successResponse('Correct Answer', [
            'correct_answer' => $correctAnswer,
            'score' => $user->score,
            'wrong_answer' => $wrongAnswers,
        ]);
    }
}
