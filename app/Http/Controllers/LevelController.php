<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    public function index(Request $request){
        $levels = DB::table('level')
        ->where('active', 1)
        ->select('id', 'title')
        ->get();
        return response()->json([
            "success" => "true",
            "levels" => $levels
        ]);
    }
}
