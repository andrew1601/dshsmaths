<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Test;
use App\Models\Question;

class Paper extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function marks()
    {
        return $this->hasManyThrough(Mark::class, Question::class);
    }

    public function marks_for_student(Student $student)
    {

    }
}
