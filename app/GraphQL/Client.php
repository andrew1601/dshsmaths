<?php


namespace App\GraphQL;


use GraphQL\Results;

class Client extends \GraphQL\Client
{
    public function runQuery($query, bool $resultsAsArray = false, array $variables = [[]]): Results
    {
        // Dealt with Arbor GraphQL variable bug
        return parent::runQuery($query, $resultsAsArray, $variables);
    }

    public function runRawQuery(string $queryString, $resultsAsArray = false, array $variables = [[]]): Results
    {
        // Dealt with Arbor GraphQL variable bug
        return parent::runRawQuery($queryString, $resultsAsArray, $variables);
    }

}
