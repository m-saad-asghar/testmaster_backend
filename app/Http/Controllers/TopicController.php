<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class TopicController extends Controller{

    public function get_topics_of_unit(Request $request, $unitId){
        $topics = DB::table('topics')
        ->where('active', 1)
        ->where('unit_id', $unitId)
        ->select('id', 'name')
        ->get();

        return response()->json([
            "success" => "true",
            "topics" => $topics
        ]);
    }
    public function add_topic_of_unit(Request $request){
        $unit = DB::table('topics')
        ->insert([
            "name" => $request -> topicName,
            "unit_id" => $request -> unitId
            
        ]);

        return response()->json([
            "success" => 1
            
        ]);
    }
}