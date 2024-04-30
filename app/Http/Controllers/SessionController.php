<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class SessionController extends Controller
{
   
    public function getSessions(Request $request){
        $sessions = DB::table('session')
        ->where('active', 1)
        ->select('id', 'title as name')
        ->get();
        return response()->json([
            "success" => 1,
            "sessions" => $sessions
        ]);
    }
}
