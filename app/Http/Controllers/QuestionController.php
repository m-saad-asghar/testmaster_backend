<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\QuestionModel;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;

class QuestionController extends Controller
{
    public function get_practice_question(Request $request) {

        if(isset($request->bookName) && $request->bookName){

            $chapters = $request->chapters;
            $book_name = $request->bookName;

            if(count($chapters) > 0 && $chapters != null && $chapters != "undefined" && $chapters != [0]){
                $book_id = DB::table("books")
                ->where("name", $book_name)
                ->value('id');

                if($book_id){
                    $question = $this->get_questions_answers($chapters);
                    if($question == "false"){
                        return response()->json([
                            "success" => "no_question"
                        ]);
                    }else{
                        return response()->json([
                            "success" => 1,
                            "question" => $question
                        ]);
                    }
                }else{
                    return response()->json([
                        "success" => "no_question"
                    ]);
                }

            }else{
                $book_id = DB::table("books")
                ->where("name", $book_name)
                ->value('id');

                if($book_id){
                    $units = DB::table("unit")
                    ->where("book_id", $book_id)
                    ->where("active", 1)
                    ->pluck("id")
                    ->toArray();

                    $question = $this->get_questions_answers($units);
                    if($question == "false"){
                        return response()->json([
                            "success" => "no_question"
                        ]);
                    }else{
                        return response()->json([
                            "success" => 1,
                            "question" => $question
                        ]);
                    }

                }else{
                    return response()->json([
                        "success" => "no_question"
                    ]);
                }
            }

        }else{
            return response()->json([
                "success" => 0,
            ]);
        }
    }

    public function get_questions_by_year(Request $request)
    {
        $perpage= $request->input("perPage");
        $exam_questions = DB::table('exam_questions')
        ->join('question_year', 'exam_questions.id', '=', 'question_year.question_id')
        ->join('exam_year', 'question_year.year_id', '=', 'exam_year.id')
        ->leftJoin('answers', 'answers.question_id', '=', 'exam_questions.id')
        ->where('exam_year.active', 1)
        ->whereIn('exam_year.name', $request->years)
        ->select('exam_questions.id', 'exam_questions.question', DB::raw('JSON_ARRAYAGG(answers.answer) as answers'))
        ->groupBy('exam_questions.id', 'exam_questions.question')
        ->paginate($perpage);

    foreach ($exam_questions as $question) {
        if ($question->answers) {
            $question->answers = json_decode($question->answers); 
        } else {
            $question->answers = [];
        }
    }

    if ($exam_questions->isEmpty()) {
        return response()->json([
            "success" => "no_questions",
        ]);
    }else{
        return response()->json([
            "success" => 1,
            "exam_questions" => $exam_questions
        ]);
    }

    // $data = $exam_questions->map(function ($question) {
    //     return [
    //         'question' => $question->question,
    //         'answers' => $question->answers,
    //     ];
    // })->toArray();

    // $pdf = new Dompdf();
    // $pdf->loadHtml('<h1>Questions By Year</h1><ol>' .
    //     collect($data)->map(function ($item) {
    //         $listItem = "<li>{$item['question']}</li>";
    //         if (isset($item['answers']) && is_array($item['answers']) && !empty($item['answers'])) {
    //             $listItem .= "<ul>";
    //             foreach ($item['answers'] as $answer) {
    //                 $listItem .= "<li>{$answer}</li>";
    //             }
    //             $listItem .= "</ul>";
    //         }
    //         return $listItem;
    //     })->implode('') .
    //     '</ol>');

    // $options = new Options();
    // $options->set('isHtml5ParserEnabled', true);
    // $pdf->setOptions($options);

    // $pdf->render();

    // return $pdf->stream('exam_questions_by_year.pdf');
    }

    public function get_questions_answers($units){

            if(count($units) > 0) {
                $topics = DB::table("topics")
                ->whereIn("unit_id", $units)
                ->where("active", 1)
                ->pluck("id")
                ->toArray();

                $questions = QuestionModel::whereIn("topic_id", $topics)
                 ->where("active", 1)
                 ->select("id", "question")
                ->with(['answers' => function($query) {
                 $query->where('active', 1);
                 }])
                ->inRandomOrder()
                ->first();

                return $questions;
            }else{
                return "false";
            }
    }

    public function get_units(Request $request) {
        if(isset($request->book_id)){

            $book_name = $request->book_name;
            $book_id = $request->book_id;

            $units = DB::table("unit")
            ->where("book_id", $book_id)
            ->where("active", 1)
            ->select("id", "name")
            ->get();

            return response()->json([
                "success" => 1,
                "units" => $units
            ]);
        }else{
            return response()->json([
                "success" => 0,
            ]);
        }
    }

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
        $user_id = Auth::id();
        $question_id = DB::table('questions')
            ->insertGetId([
                "title" => $question,
                "level_id" => $level,
                "unit_id" => $unit,
                "subject_id" => $subject,
                "exam_group" => $exam_group,
                "user_id" => $user_id,
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
    public function addQuestion(Request $request){
        $years = $request -> years;
        $questionType = count($years) != 0 ? "PastPaper" : "None";
        $answers = $request->answers;

        $id = DB::table('exam_questions')
        ->insertGetId([
            "question" => $request -> question,
            "topic_id" => $request -> topicId,
            "type" => $questionType

        ]);
        if (count($years) != 0){
            foreach ($years as $y) {
                DB::table('question_year')
                    ->insert([
                        "question_id" => $id,
                        "year_id" => $y
                    ]);
            }

        }
        foreach ($answers as $ans) {
            DB::table('answers')
                ->insert([
                    "question_id" => $id,
                    "answer" => $ans['input'],
                    "type" => ($ans['option'] == "selected") ? '1' : '0'
                ]);
        }

        return response()->json([
            "success" => "true"
        ]);
    }
}
