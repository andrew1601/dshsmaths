<?php

namespace App\Relations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Custom extends Relation {

    public function addConstraints()
    {
            $this->query
                ->select('cohorts.*')
                ->distinct()
                ->join('teaching_groups', 'cohorts.id', '=', 'teaching_groups.cohort_id')
                ->where('teaching_groups.assessment_source_id', $this->parent->id);
    }

    public function addEagerConstraints(array $models)
    {
        $this->query->orWhereIn('teaching_groups.assessment_source_id', collect($models)->pluck('id'));
    }

    public function initRelation(array $models, $relation): array
    {
        foreach($models as $model) {
            $model->setRelation($relation, $this->related->newCollection());
        }

        return $models;
    }

    public function match(array $models, Collection $results, $relation): array
    {
        if ($results->isEmpty()) {
            return $models;
        }

        foreach ($models as $model) {
            $model->setRelation($relation, $results);
        }

        return $models;

    }

    public function getResults()
    {
        return $this->query->get();
    }
}

