<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function scopeForStudent($query, Student $student)
    {
        return $query->where('student_upn', $student->upn);
    }

    public function scopeForQuestion($query, Question $question)
    {
        return $query->where('question_id', $question->id);
    }

    public function scopeForStudents($query, Collection $students)
    {
        $student_upns = $students->only('upn')->toArray();
        return $query->whereIn('student_upn', $student_upns);
    }

    public function scopeForPaper($query, Paper $paper)
    {
        $question_ids = $paper->questions->modelKeys();
        return $query->whereIn('question_id', $question_ids)->orderBy('question_id');
    }
}
