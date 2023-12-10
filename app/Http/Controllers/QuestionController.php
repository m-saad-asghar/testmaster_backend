<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function add_question(Request $request)
    {
        $answers = $request->answers;
        $exam_group = $request->exam_group;
        $level = $request->level;
        $question = $request->question;
        $subject = $request->subject;
        $unit = $request->unit;
        $year = $request->year;
        $question = $request->question;

        $question_id = DB::table('questions')
            ->insertGetId([
                "title" => $question,
                "level_id" => $level,
                "unit_id" => $unit,
                "subject_id" => $subject,
                "exam_group" => $exam_group,
                "user_id" => 6,
                "type" => (count($year) == 0) ? "mcq" : "pastpaper"
            ]);

        if (count($year) != 0) {
            foreach ($year as $y) {
                DB::table('question_year')
                    ->insert([
                        "question_id" => $question_id,
                        "year_id" => $y
                    ]);
            }
        }

        foreach ($answers as $ans) {
            DB::table('answers')
                ->insert([
                    "question_id" => $question_id,
                    "answer" => $ans['input'],
                    "type" => ($ans['option'] == "selected") ? '1' : '0'
                ]);
        }

        return response()->json([
            "success" => "true"
        ]);

    }
}
