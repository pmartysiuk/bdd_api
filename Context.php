<?php

use Behat\Gherkin\Node\PyStringNode;
use Carbon\Carbon;



class Context
{

    /**
     * @Given I have the payload: :json
     */
    public function iHaveThePayload(PyStringNode $json)
    {
    }

    /**
     * @Given I have :header header is :value
     */
    public function iHaveHeaderIs($header, $value)
    {
    }

    /**
     * @Given I have json fixture :jsonName
     */
    public function iHaveJsonFixture($jsonName)
    {
    }

    /**
     * @When /^I send (POST|GET|PATCH|DELETE|PUT|HEAD|OPTIONS) request to "([^"]*)"$/
     */
    public function iSendRequestTo($method, $url)
    {
    }

    /**
     * @Then response status code should be :code
     */
    public function responseStatusCodeShouldBe($code)
    {
    }

    /**
     * @Then JSON response body should be like: :json
     */
    public function jsonResponseBodyShouldBeLike(PyStringNode $json)
    {
    }

    /**
     * @Then JSON response body should be like in :path
     */
    public function jsonResponseBodyShouldBeLikeInFixture($path)
    {
    }

    /**
     * @Given I update json fixture by: :string
     */
    public function iUpdateJsonFixtureBy(PyStringNode $string)
    {
    }

    /**
     * @Given I save :property value
     */
    public function iSaveValue($property)
    {
    }

    /**
     * @Given I save :property value as :var
     */
    public function iSaveValueAs($property, $var)
    {
    }
}
