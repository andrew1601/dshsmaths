<?php

namespace App\Relations;

use Closure;

trait HasCustomRelations
{

    public function custom($related, Closure $baseConstraints, Closure $eagerConstraints, Closure $eagerMatcher): Custom
    {
        $instance = new $related;
        $query = $instance->newQuery();

        return new Custom($query, $this, $baseConstraints, $eagerConstraints, $eagerMatcher);
    }
}
