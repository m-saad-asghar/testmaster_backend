<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function generateTestReference() {
        $microtime = microtime(true);
        $uniqueString = uniqid($microtime, true);
        $hash = md5($uniqueString);
        $intHash = base_convert($hash, 16, 10);
        $uniqueKey = substr($intHash, 0, 5);
        $uniqueKey = str_pad($uniqueKey, 5, '0', STR_PAD_LEFT);
        return "PP" . $uniqueKey;
    }
    public function save_test_logs(Request $request) {

        if($request->test_reference == ""){
            $user_id = Auth::id();
            $test_reference = $this->generateTestReference();
                DB::table('test_record')
                ->insert([
                "user_id" => $user_id,
                "type" => $request->test_type,
                "reference" => $test_reference
                ]);

            $id = DB::table('test_logs')
            ->insertGetId([
             "question_id" => $request->question_id,
             "answer_id" => $request->answer_id,
             "status" => $request->status,
             "reference_id" => $test_reference
            ]);

                 if ($id >= 0){
                return response()->json([
                    "success" => 1,
                    "test_reference" => $test_reference,
                    "log_id" => $id
                ]);
            }else{
                return response()->json([
                    "success" => 0,
                ]);
            }

        }
        else{
            $prev_record = DB::table('test_logs')
            ->where("id", $request->test_log_id)
            ->first();
            if($prev_record) {
                $id = DB::table('test_logs')
                ->where('id', $request->test_log_id)
                ->update([
                 "answer_id" => $request->answer_id,
                ]);
                if ($id >= 0){
                    return response()->json([
                        "success" => 1
                    ]);
                }else{
                    return response()->json([
                        "success" => 0,
                    ]);
                }
            }else{
                $id = DB::table('test_logs')
            ->insertGetId([
             "question_id" => $request->question_id,
             "answer_id" => $request->answer_id,
             "status" => $request->status,
             "reference_id" => $request->test_reference
            ]);

                 if ($id >= 0){
                return response()->json([
                    "success" => 1,
                    "log_id" => $id
                ]);
            }else{
                return response()->json([
                    "success" => 0,
                ]);
            }
            }
        }
    }
}
