<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index(Request $request){
        $subjects = DB::table('subject')
        ->where('active', 1)
        ->select('id', 'title')
        ->get();
        return response()->json([
            "success" => "true",
            "subjects" => $subjects
        ]);
    }

    public function get_specific_subject($id){
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
