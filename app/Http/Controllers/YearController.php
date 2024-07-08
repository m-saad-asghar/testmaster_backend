<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class YearController extends Controller
{
    public function index(Request $request){
        $years = DB::table('year')
        ->where('active', 1)
        ->select('id', 'number')
        ->get();
        return response()->json([
            "success" => "true",
            "years" => $years
        ]);
    }
    
    public function get_exam_years(Request $request){
        $years = DB::table('exam_year')
        ->where('active', 1)
        ->select('id', 'name')
        ->get();
        return response()->json([
            "success" => "true",
            "years" => $years
        ]);
    }
    public function addYear(Request $request){
        $id = DB::table('exam_year')
        ->insertGetId([
            "name" => $request -> name,
            "board" => $request -> board,
            "exam_group" => $request -> exam_group,
            "session" => $request -> session,
            "year" => $request -> year

        ]);

        if ($id > 0){
            return response()->json([
                "success" => 1
            ]);
        }
        else{
            return response()->json([
                "success" => 0
            ]);
        }
    }
    
}
