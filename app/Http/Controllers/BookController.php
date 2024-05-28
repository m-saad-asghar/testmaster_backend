<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Mockery\Undefined;
use App\Models\BookModel;

class BookController extends Controller
{

    public function get_selected_books(Request $request) {
        $books = BookModel::where("subject_id", $request->subject_id)
        ->where("active", 1)
        ->orderBy("name", "asc")
        ->select(["id", "name"])
        ->get();
        
        return response()->json([
            "success" => 1,
            "books" => $books
        ]);
    }

    public function add_new_book(Request $request)
    {
        $id = DB::table('books')
            ->insertGetId([
                "name" => $request->bookName,
                "subject_id" => $request->subject,
                "level_id" => $request->level,
                "publisher" => $request->publisher
            ]);

        if ($id != null && $id != "" && $id != 'undefined') {
            $syllabusArray = $request->syllabus;
            foreach ($syllabusArray as $syllabusId) {
                DB::table('book_syllabus')->insert([
                    'syllabus_id' => $syllabusId,
                    'book_id' => $id,
                ]);
            }


            $books = DB::table('books')
                ->leftJoin('book_syllabus', 'books.id', '=', 'book_syllabus.book_id')
                ->leftJoin('subject', 'books.subject_id', '=', 'subject.id')
                ->leftJoin('level', 'books.level_id', '=', 'level.id')
                ->leftJoin('syllabus', 'syllabus.id', '=', 'book_syllabus.syllabus_id')
                ->select(
                    'books.id as id',
                    'books.name as bookName',
                    'subject.title as subject',
                    'books.subject_id as subject_id',
                    'level.title as level',
                    'books.level_id as level_id',
                    'books.publisher as publisher',
                    DB::raw('GROUP_CONCAT(syllabus.name) as syllabus'),
                    DB::raw('GROUP_CONCAT(syllabus.id) as syllabus_id'),
                    'books.active as status',
                    'books.created_at as created_at',
                )
                ->groupBy('books.id', 'books.name', 'books.subject_id', 'books.level_id', 'subject.title', 'level.title', 'books.publisher', 'books.active', 'books.created_at')
                ->get();

            return response()->json([
                "success" => 1,
                "books" => $books
            ]);
        } else {
            return response()->json([
                "success" => 0
            ]);
        }
    }
public function getBooks(Request $request){
    $books = DB::table('books')
    ->select(
        'books.id as id',
        'books.name as bookName',
    )
    ->get();

    return response()->json([
        "success" => 1,
        "books" => $books
    ]);
}
    public function get_books(Request $request)
    {
        $searchTerm = $request->search_term;
        
        $books = DB::table('books')
        ->leftJoin('book_syllabus', 'books.id', '=', 'book_syllabus.book_id')
        ->leftJoin('subject', 'books.subject_id', '=', 'subject.id')
        ->leftJoin('level', 'books.level_id', '=', 'level.id')
        ->leftJoin('syllabus', 'syllabus.id', '=', 'book_syllabus.syllabus_id')
        ->where(function ($query) use ($request) {
            if($request->search_term !== ""){
                $query->where('books.name', 'LIKE', '%' . $request->search_term . '%')
                    ->orWhere('books.publisher', 'LIKE', '%' . $request->search_term . '%')
                    ->orWhere('subject.title', 'LIKE', '%' . $request->search_term . '%')
                    ->orWhere('level.title', 'LIKE', '%' . $request->search_term . '%')
                    ->orWhere('syllabus.name', 'LIKE', '%' . $request->search_term . '%');
            }
        })
        ->select(
            'books.id as id',
            'books.name as bookName',
            'subject.title as subject',
            'books.subject_id as subject_id',
            'level.title as level',
            'books.level_id as level_id',
            'books.publisher as publisher',
            DB::raw('GROUP_CONCAT(syllabus.name) as syllabus'),
            DB::raw('GROUP_CONCAT(syllabus.id) as syllabus_id'),
            'books.active as status',
            'books.created_at as created_at',
        )
        ->groupBy('books.id', 'books.name', 'books.subject_id', 'books.level_id', 'subject.title', 'level.title', 'books.publisher', 'books.active', 'books.created_at')
        ->get();

    return response()->json([
        "success" => 1,
        "books" => $books
    ]);
    }

    public function change_status_book(Request $request, $id){
        $result = DB::table("books")->where("id", $id)->update([
            "active" => ($request->status == true) ? "1" : "0",
        ]);
        if ($result == 1){
            return response()->json([
                "success" => 1
            ]);
        }else{
            return response()->json([
                "success" => 0
            ]);
        }
    }

    public function update_book(Request $request, $id){
        $result = DB::table("books")->where("id", $id)->update([
            "level_id" => $request->level,
            "publisher" => $request->publisher,
            "subject_id" => $request->subject,
        ]);
        if ($result == 1){
            $delete_status = DB::table('book_syllabus')
           ->where('book_id', '=', $id)
           ->delete();
           if($delete_status != null && $delete_status != "" && $delete_status != "undefined"){

            foreach ($request->syllabus as $syllabus) {
                DB::table('book_syllabus')
                ->insert([
                    "book_id" => $id,
                    "syllabus_id" => $syllabus
                ]);
            }
           }
            
           $books = DB::table('books')
           ->leftJoin('book_syllabus', 'books.id', '=', 'book_syllabus.book_id')
           ->leftJoin('subject', 'books.subject_id', '=', 'subject.id')
           ->leftJoin('level', 'books.level_id', '=', 'level.id')
           ->leftJoin('syllabus', 'syllabus.id', '=', 'book_syllabus.syllabus_id')
           ->select(
               'books.id as id',
               'books.name as bookName',
               'subject.title as subject',
               'books.subject_id as subject_id',
               'level.title as level',
               'books.level_id as level_id',
               'books.publisher as publisher',
               DB::raw('GROUP_CONCAT(syllabus.name) as syllabus'),
               DB::raw('GROUP_CONCAT(syllabus.id) as syllabus_id'),
               'books.active as status',
               'books.created_at as created_at',
           )
           ->groupBy('books.id', 'books.name', 'books.subject_id', 'books.level_id', 'subject.title', 'level.title', 'books.publisher', 'books.active', 'books.created_at')
           ->get();
   
       return response()->json([
           "success" => 1,
           "books" => $books
       ]);
        }else{
            return response()->json([
                "success" => 0
            ]);
        }
    }
}
