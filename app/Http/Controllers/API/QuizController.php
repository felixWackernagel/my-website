<?php

namespace App\Http\Controllers\API;

use App\Models\Quiz;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('location')->orderBy( 'number', 'DESC' )->get();
        $quizzes->makeHidden(['location_id']);
        return response()->json( [
            "quizzes" => $quizzes
        ], 200);
    }

    public function store( Request $request )
    {
        $this->validate( $request, [
            "number" => "required|min_digits:1|unique:quizzes,number",
            "location_id" => "nullable|numeric|exists:locations,id"
        ] );

        $startedAt = null;
        if( $request->has( 'started_at' ) && !empty( $request->started_at ) )
        {
           $startedAt = Carbon::createFromFormat( 'Y-m-d\TH:i', $request->started_at )->toDateTimeString();
        }

        $quiz = Quiz::create([
            "number" => $request->number,
            "quiz_master" => $request->quiz_master,
            "quiz_winner" => $request->quiz_winner,
            "started_at" => $startedAt,
            "location_id" => $request->location_id
        ]);

        return response()->json( $quiz, 201 );
    }

    public function update( Request $request, $id )
    {
        $quiz = Quiz::find( $id );

        $this->validate( $request, [
            "number" => "required|min_digits:1|unique:quizzes,number," . $id,
            "location_id" => "nullable|numeric|exists:locations,id"
        ] );

        $startedAt = null;
        if( $request->has( 'started_at' ) && !empty( $request->started_at ) )
        {
           $startedAt = Carbon::createFromFormat( 'Y-m-d\TH:i', $request->started_at )->toDateTimeString();
        }

        $quiz->number = $request->number;
        $quiz->quiz_master = $request->quiz_master;
        $quiz->quiz_winner = $request->quiz_winner;
        $quiz->started_at = $startedAt;
        $quiz->location_id = $request->location_id;
        $quiz->save();

        return response()->json( $quiz, 200 ); // 200 = success
    }

    public function delete( Request $request, $id )
    {
        $quiz = Quiz::findOrFail( $id );
        $quiz->delete();

        return response()->json( null, 200 );
    }
}
