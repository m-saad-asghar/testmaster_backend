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
}
