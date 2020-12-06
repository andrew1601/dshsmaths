<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Paper;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }


}
