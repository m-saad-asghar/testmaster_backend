<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SubjectModel;

class SubjectController extends Controller
{
    public function get_subjects(Request $request) {
        $subjects = SubjectModel::with(['books' => function ($query) {
            $query->where('books.active', 1)
                  ->join('level', 'books.level_id', '=', 'level.id')
                  ->select('books.*', 'level.title as level_title');
        }])->get();
        return response()->json([
            "success" => 1,
            "subjects" => $subjects
        ]);
    }

    public function index(Request $request)
    {
        $subjects = DB::table('subject')
            ->where('active', 1)
            ->select('id', 'title')
            ->get();
        return response()->json([
            "success" => "true",
            "subjects" => $subjects
        ]);
    }

    public function get_all_subjects(Request $request) {
        $subjects = SubjectModel::where("active", 1)
        ->orderBy("title", "asc")
        ->select(["id", "title"])
        ->get();
        
        return response()->json([
            "success" => 1,
            "subjects" => $subjects
        ]);
    }

    public function get_specific_subject($id)
    {
        $subjects = DB::table('subject')
            ->leftJoin('level_subject', 'level_subject.subject_id', 'subject.id')
            ->where('subject.active', 1)
            ->where('level_subject.level_id', $id)
            ->select('subject.id', 'subject.title')
            ->get();
        return response()->json([
            "success" => "true",
            "subjects" => $subjects
        ]);
    }
}
