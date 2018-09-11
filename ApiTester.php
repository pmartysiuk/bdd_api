<?php

use Behat\Gherkin\Node\PyStringNode;
use Carbon\Carbon;


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;
    protected $parameters;
    protected $payload;
    public $properties;


    /**
     * @Given I have the payload: :json
     */
    public function iHaveThePayload(PyStringNode $json)
    {
        $this->payload = $this->parseData((string)$json);
    }

    /**
     * @Given I have :header header is :value
     */
    public function iHaveHeaderIs($header, $value)
    {
        $this->haveHttpHeader($header, $value);
    }

    /**
     * @Given I wait for :time
     */
    public function iWaitFor($time)
    {
        sleep($time);
    }

    /**
     * @When /^I send (POST|GET|PATCH|DELETE|PUT|HEAD|OPTIONS) request to "([^"]*)"$/
     */
    public function iSendRequestTo($method, $url)
    {
        $url = $this->parseData($url);
        $method = "send" . $method;
        $this->$method($url, $this->payload);
    }

    /**
     * @Then response status code should be :code
     */
    public function responseStatusCodeShouldBe($code)
    {
        $this->seeResponseCodeIs($code);
    }

    /**
     * @Then JSON response body should be like: :json
     */
    public function jsonResponseBodyShouldBeLike(PyStringNode $json)
    {
        $this->seeResponseIsJson();
        $json = json_decode($this->parseData((string)$json), true);
        $this->seeResponseContainsJson($json);
    }

    /**
     * @Then JSON response body should be like in :path
     */
    public function jsonResponseBodyShouldBeLikeInFixture($path)
    {
        $this->seeResponseIsJson();
        $filePath = $this->getFixturesPath() . $path . '.json';

        if (!is_readable($filePath)) {
            throw new \Exception(sprintf(
                    'Can not read fixture file in path: %s'
                    , $filePath
                )
            );
        }
        $response = json_decode($this->parseData((string)file_get_contents($filePath)), true);
        $this->seeResponseContainsJson($response);
    }

    /**
     * @Given I update json fixture by: :string
     */
    public function iUpdateJsonFixtureBy(PyStringNode $string)
    {
        $string = $this->parseData($string);
        $replacementData = json_decode($string, true);
        $payload = json_decode($this->payload, true);

        foreach ($replacementData as $replacementKey => $replacementValue) {
            array_set($payload, $replacementKey, $replacementValue);
        }

        $this->payload = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * @Given I save :property value
     */
    public function iSaveValue($property)
    {
        $properties = json_decode($this->properties, true);
        $response = json_decode($this->grabResponse(), true);
        $value = $response["$property"];
        array_set($properties, $property, $value);
        $this->properties = json_encode($properties, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $this->properties = $this->parseData((string)$this->properties);
    }

    /**
     * @Given I save :property value as :var
     */
    public function iSaveValueAs($property, $var)
    {
        $properties = json_decode($this->properties, true);
        $response = json_decode($this->grabResponse(), true);

        $path = explode(".", $property);
        $value = $response;
        foreach ($path as $ndx) {
            $value = $value[$ndx];
        }
        array_set($properties, $var, $value);
        $this->properties = json_encode($properties, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $this->properties = $this->parseData((string)$this->properties);
    }

    /**
     * @Given I save global property :key value as :value
     */
    public function iSaveGlobalPropertyValueAs($key, $value)
    {
        $properties = json_decode($this->properties, true);

        array_set($properties, $key, $value);
        $this->properties = json_encode($properties, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $this->properties = $this->parseData((string)$this->properties);
    }

    private function parseData($string)
    {
        $replaceFunction = function (&$in, array $placeholders, array $replacements) {
            foreach ($placeholders as $value) {
                list($replace, $parameter) = $value;
                $in = str_replace($replace, array_get($replacements, $parameter, $replace), $in);
            }
        };

        if (preg_match_all('/<random:(.+?)(?:,(.+))?>/', $string, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $k => list($replace, $number)) {
                $min = pow(10, $number - 1);
                $max = pow(10, $number) - 1;
                $string = str_replace($replace, rand($min, $max), $string);
            }
        }

        if (preg_match_all('/<date:(.+?)(?:,(.+))?>/', $string, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $k => list($replace, $format)) {
                $time = array_get($matches[$k], 2, 'now');

                list($time, $format) = array_map(function ($value) {
                    return trim($value, " \"'");
                }, [$time, $format]
                );
                $string = str_replace($replace, date($format, strtotime($time)), $string);
            }
        }

        $placeholders = [];
        if (preg_match_all('/<([\w.]+)>/', $string, $placeholders, PREG_SET_ORDER)) {
            $response = json_decode($this->grabResponse(), true);
            $properties = json_decode($this->properties, true);
            foreach ($placeholders as $k => list($replace, $format)) {
                if (array_key_exists($format, $response)) {
                    $replaceFunction($string, $placeholders, $response);
                } elseif ($properties <> null and array_key_exists($format, $properties)) {
                    $replaceFunction($string, $placeholders, $properties);
                }
            }
        }

        return $string;
    }

    /**
     * Path to fixtures folder in child class, that extended this abstract class.
     *
     * @return string
     */
    protected function getFixturesPath()
    {
        $classInfo = new \ReflectionClass($this);
        return dirname($classInfo->getFileName()) . '/../api/';
    }

    /**
     * @Given I have json fixture :jsonName
     */
    public function iHaveJsonFixture($jsonName)
    {
        $filePath = $this->getFixturesPath() . $jsonName . '.json';

        if (!is_readable($filePath)) {
            throw new \Exception(sprintf(
                    'Can not read fixture file in path: %s'
                    , $filePath
                )
            );
        }
        $this->payload = $this->parseData((string)file_get_contents($filePath));
    }

    /**
     * @param string $file the fixture file
     * @return \Illuminate\Support\Collection
     */
    public function parse($file)
    {
        if (!is_readable($file)) {
            return false;
        }

        $rows = $this->getFixtureData($file);
        $keys = array_shift($rows);

        $data = [];
        foreach ($rows as $row) {
            $data[] = array_combine($keys, $row);
        }

        return new \Illuminate\Support\Collection($this->prepareDataTypes($data));
    }

    public function getFixtureData($file)
    {
        $data = [];
        foreach (file($file, FILE_SKIP_EMPTY_LINES) as $line) {
            if (strpos(trim($line), '|') === 0) {
                $data[] = array_slice(array_map('trim', explode('|', $line)), 1, -1);
            }
        }

        return $data;
    }

    private function prepareDataTypes($data)
    {
        array_walk_recursive($data, function (&$value) {
            if (is_string($value) && strtolower($value) == 'null') {
                $value = null;
            }
        }
        );

        return $data;
    }
}
