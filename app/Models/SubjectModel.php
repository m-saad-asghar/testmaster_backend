<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectModel extends Model
{
    use HasFactory;

    protected $table = 'subject';

    public function books()
    {
        return $this->hasMany(BookModel::class, 'subject_id', 'id');
    }
}
