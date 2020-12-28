<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

use App\Scopes\ChronoScope;

class Test extends Model
{
    use HasFactory;
    use HasRelationships;

    protected static function booted()
    {
        static::addGlobalScope(new ChronoScope);
    }


    protected $guarded = [];

    public function assessment_source()
    {
        return $this->belongsTo(AssessmentSource::class, 'assessment_source_id', 'id');
    }

    public function papers()
    {
        return $this->hasMany(Paper::class);
    }

    public function grade_boundaries()
    {
        return $this->hasMany(GradeBoundary::class);
    }

    public function teaching_groups()
    {
        return $this->belongsToMany(TeachingGroup::class);
    }

    public function grade_from_total_marks($total_marks)
    {
        $current_grade = "";
        foreach($this->grade_boundaries as $grade_boundary) {
            if ($total_marks >= $grade_boundary->mark)
                $current_grade = $grade_boundary->grade;
            else
                break;
        }

        return $current_grade;
    }

    public function marks()
    {
        return $this->hasManyDeep(Mark::class, [Paper::class, Question::class]);
    }
}
