<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Mockery\Undefined;

class BookController extends Controller
{
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
    public function get_books(Request $request)
    {
        $books = DB::table('books')
        ->select(
            'books.id as id',
            'books.name as bookname',
        )
        ->get();
        
        return response()->json([
            "success" => 1,
            "books" => $books
        ]);

    }
}
