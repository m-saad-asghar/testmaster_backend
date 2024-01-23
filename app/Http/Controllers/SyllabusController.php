<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyllabusController extends Controller
{
    public function index(Request $request)
    {
        $syllabus = DB::table('syllabus')
            ->where('active', 1)
            ->select('id', 'name')
            ->get();
        return response()->json([
            "success" => "true",
            "syllabus" => $syllabus
        ]);
    }
}
