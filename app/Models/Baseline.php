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

    public function igr()
    {
        $grades = [
            // Letter grades
            'E' => 'E-D', 'D' => 'D-B', 'C' => 'C-A', 'B' => 'B-A*', 'A' => 'A-A*', 'A*' => 'A*',
            // Number grades
            '1' => '1-3', '2' => '2-4', '3' => '3-5', '4' => '4-6', '5', '5-7', '6' => '6-8', '7' => '7-9', '8' => '8-9', '9' => '9',
        ];

        return $grades[$this->grade];
    }
}
