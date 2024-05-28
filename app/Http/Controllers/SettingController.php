<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingModel;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{

    public function add_test(Request $request) {

        $testExist = SettingModel::where("subject_id", $request->subject_id)
            ->where("book_id", $request->book_id)
            ->exists();

        if (!$testExist) {
            $result = DB::table("setting")->insert([
                "subject_id" => $request -> subject_id,
                "subject_name" => $request -> subject_name,
                "book_id" => $request -> book_id,
                "book_name" => $request -> book_name,
                "user_id" => 6,
                "active" => 1
            ]);
            if ($result == 1){
                $test_data = DB::table("setting")
                ->where("active", 1)
                ->where("user_id", 6)
                    ->orderBy("id", "DESC")
                    ->select(["subject_id", "subject_name", "book_id", "book_name"])
                    ->get();
                return response()->json([
                    "success" => 1,
                    "category" => $test_data
                ]);
            }else if ($result == 0) {
                return response()->json([
                    "success" => "went_wrong"
                ]);
            }else{
                return response()->json([
                    "success" => 0
                ]);
            }
            
        } else if($testExist) {
            return response()->json([
                "success" => "already_exist"
            ]);
        } else{
            return response()->json([
                "success" => 0
            ]);
        }
        
        
    }

    public function get_setting_with_units(Request $request){

        $setting = SettingModel::with(['units' => function ($query) {
            $query->where('unit.active', 1);
        }])
        ->where("user_id", 6)
        ->orderBy("id", "DESC")
        ->get(['id', 'subject_id', 'subject_name', 'book_id', 'book_name']);
            return response()->json([
                "success" => 1,
                "setting" => $setting
            ]);
    }

    public function get_setting(Request $request) {
        $setting = SettingModel::where("active", 1)
        ->where("user_id", 6)
        ->orderBy("id", "DESC")
        ->select(["id", "subject_id", "subject_name", "book_id", "book_name"])
        ->get();
            return response()->json([
                "success" => 1,
                "setting" => $setting
            ]);
    }
}
