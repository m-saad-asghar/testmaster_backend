<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ExamGroupController extends Controller
{
    public function index(Request $request){
        $exam_groups = DB::table('exam_group')
        ->where('active', 1)
        ->select('id', 'title')
        ->get();
        return response()->json([
            "success" => "true",
            "exam_groups" => $exam_groups
        ]);
    }
}
