<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $history = null;
        if (auth()->user()->role == 'admin') {
            $history = History::all();
        } else {
            $history = History::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->get();
        }
        return $this->successResponse($history);
    }

    public function create(Request $request)
    {
        $history = History::create([
            'user_id' => auth()->user()->id,
            'xp' => $request->xp,
            'type' => $request->type,
        ]);
        return $this->successResponse($history, 'History created successfully', 201);
    }
}
