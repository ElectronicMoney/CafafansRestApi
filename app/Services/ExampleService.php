<?php

namespace App\Services;

use App\Traits\ApiHttpClient;

class  ExampleService
{
    use ApiHttpClient;

    /**
     * The baseUri to consume the examples service
     * @var string
     */
    public $baseUri;
    /**
     * Creating a new Example instance.
     *
     * @return void
     */
    public function __construct() {
        $this->baseUri = config('services.example_service.base_uri');
    }

    /**
     * Fetching the list of examples from the Example Service
     * @param void
     * @return string
     */
    public function getExamples() {
        return $this->httpRequest('GET', 'examples')->getBody();
    }

    /**
     * creating an example from examples micro service
     * @param array $data
     * @return string
     */
    public function createExample($data) {
        return $this->httpRequest('POST', 'examples', $data)->getBody();
    }

    /**
     * Fetching an example instance from examples micro service
     * @param int $exampleId
     * @return string
     */
    public function getExample($exampleId) {
        return $this->httpRequest('GET', "examples/{$exampleId}")->getBody();
    }

    /**
     * updating an example instance using examples micro service
     * @param array $data
     * @param int $exampleId
     * @return string
     */
    public function editExample($data, $exampleId) {
        return $this->httpRequest('PUT', "examples/{$exampleId}", $data)->getBody();
    }

    /**
     * Deleting an example instance from examples micro service
     * @param int $exampleId
     * @return string
     */
    public function deleteExample($exampleId) {
        return $this->httpRequest('DELETE', "examples/{$exampleId}")->getBody();
    }
}
