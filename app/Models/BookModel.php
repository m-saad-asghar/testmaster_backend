<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookModel extends Model
{
    use HasFactory;
    protected $table = 'books';

    public function subject()
    {
        return $this->belongsTo(SubjectModel::class, 'subject_id', 'id');
    }
}
