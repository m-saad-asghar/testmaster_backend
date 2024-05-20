<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoardController extends Controller
{
    

    public function add_new_board(Request $request)
    {
       $id = DB::table('board')
       ->insert([
        "name" => $request -> name
       ]);

       if ($id >= 0){
        $boards = DB::table('board')
       ->select(
        'id',
        'name',
        'active',
        'created_at'
       )
       ->get();

       return response()->json([
        "success" => 1,
        "boards" => $boards
    ]);

       }
       else{
        return response()->json([
            "success" => 0,
            
        ]);
       }

       
    }
    public function getBoards(Request $request){
        $boards = DB::table('board')
       ->select(
        'id',
        'name'
        
       )
       ->get();

       return response()->json([
        "success" => 1,
        "boards" => $boards
    ]);
    }
}
