<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogModel;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function save_test_logs(Request $request) {

        $id = DB::table('test_logs')
        ->insert([
         "user_id" => 6,
         "question_id" => $request->question_id,
         "answer_id" => $request->answer_id,
         "status" => $request->status,
         "test_type" => $request->test_type
        ]);

        if ($id >= 0){
            return response()->json([
                "success" => 1,
            ]);
        }else{
            return response()->json([
                "success" => 0,
            ]);
        }
    }
}
