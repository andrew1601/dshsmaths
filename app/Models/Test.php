<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Paper;
use App\Models\Question;
use App\Models\GradeBoundary;

class Test extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function papers()
    {
        return $this->hasMany(Paper::class);
    }

    public function grade_boundaries()
    {
        return $this->hasMany(GradeBoundary::class);
    }
}
