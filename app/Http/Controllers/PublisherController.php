<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublisherController extends Controller
{
    public function index(Request $request){
        $publishers = DB::table('publishers')
        ->where('active', 1)
        ->select('id', 'name')
        ->get();
        return response()->json([
            "success" => "true",
            "publishers" => $publishers
        ]);
    }
}
