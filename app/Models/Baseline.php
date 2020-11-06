<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\AssessmentSource;
use App\Models\Student;

class Baseline extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $guarded = [];

    public function assessment_source()
    {
        return $this->belongsTo(AssessmentSource::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
