<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request){
        $units = DB::table('unit')
        ->where('active', 1)
        ->select('id', 'number', 'name', 'level_id', 'subject_id')
        ->get();
        return response()->json([
            "success" => "true",
            "units" => $units
        ]);
    }

    public function get_specific_units(Request $request){
        $subject_id = $request->subject_id;
        $level_id = $request->level_id;
        $units = DB::table('unit')
        ->where('active', 1)
        ->where('subject_id', $subject_id)
        ->where('level_id', $level_id)
        ->select('id', 'number', 'name', 'level_id', 'subject_id')
        ->get();
        return response()->json([
            "success" => "true",
            "units" => $units
        ]);
    }
    public function get_units_of_book(Request $request, $bookId){
        $units = DB::table('unit')
        ->where('active', 1)
        ->where('book_id', $bookId)
        ->select('id', 'name')
        ->get();

        return response()->json([
            "success" => "true",
            "units" => $units
        ]);

    }
}
