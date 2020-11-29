<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

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
}
