<?php


namespace App\GraphQL;


use GraphQL\Results;
use Psr\Http\Client\ClientInterface;

class Client extends \GraphQL\Client
{
    public function __construct(string $endpointUrl, array $authorizationHeaders = [], array $httpOptions = [], ClientInterface $httpClient = null, string $requestMethod = 'POST')
    {
        parent::__construct($endpointUrl, $authorizationHeaders, $httpOptions, $httpClient, $requestMethod);
    }

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
