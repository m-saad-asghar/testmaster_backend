<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitModel extends Model
{
    use HasFactory;

    protected $table = 'unit';

    public function setting()
    {
        return $this->belongsTo(SettingModel::class, 'book_id', 'book_id');
    }
}
