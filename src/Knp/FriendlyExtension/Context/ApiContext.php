<?php

namespace Knp\FriendlyExtension\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Guzzle\Http\Exception\BadResponseException;
use Knp\FriendlyExtension\Http\Security\HttpBasicExtension;
use Knp\FriendlyExtension\Http\Security\OauthExtension;

class ApiContext extends Context
{
    /**
     * @Given /^I prepare a (?<method>[A-Za-z]+) request on "(?<page>[^"].*)?"$/
     * @Given /^I prepare a (?<method>[A-Za-z]+) request on the (.*) (?<hasPage>page|resource)$/
     * @Given /^I prepare a (?<method>[A-Za-z]+) request on the (.*) (?<hasPage>page|resource) with:?$/
     */
    public function iPrepareRequest($method, $page, $hasPage = false, TableNode $table = null)
    {
        $hasPage = (bool)$hasPage;
        $method  = strtoupper($method);

        if ($hasPage) {
            $path = $this->get('page')->getPagePath($page, $table);
        } else {
            $path = $page;
        }

        $this->get('api')->setCall($method, $path);
    }

    /**
     * @Given /^I specified the following request http basic credentials:?$/
     */
    public function iSpecifiedTheFollowingBasicAuthentication(TableNode $credentialsTable)
    {
        $this->get('api')->setSecurityCredentials(new HttpBasicExtension, $credentialsTable->getRowsHash());
    }

    /**
     * @Given /^I specified the following request oauth credentials:?$/
     */
    public function iSpecifiedTheFollowingOauthCredentials(TableNode $credentialsTable)
    {
        $this->get('api')->setSecurityCredentials(new OauthExtension, $credentialsTable->getRowsHash());
    }

    /**
     * @Given /^I specified the following request headers:?$/
     */
    public function iSpecifiedHeaders(TableNode $table)
    {
        $this->get('api')->setHeaders($table->getRowsHash());
    }

    /**
     * @Given /^I specified the following request queries:?$/
     */
    public function iSpecifiedQueries(TableNode $table)
    {
        $this->get('api')->setQueries($table->getRowsHash());
    }

    /**
     * @Given /^I specified the following request body:?$/
     */
    public function iSpecifiedTheBody($data)
    {
        if (is_object($data) and $data instanceof TableNode) {
            $data = $data->getRowsHash();
        }

        if (is_object($data) and $data instanceof PyStringNode) {
            $data = (string)$data;
        }

        $this->get('api')->setBody($data);
    }

    /**
     * @Given /^I specified the following request data:?$/
     */
    public function iSpecifiedData(TableNode $dataTable)
    {
        $this->get('api')->setData($dataTable->getRowsHash());
    }

    /**
     * @Given /^I specified the following request cookies:?$/
     */
    public function iSpecifiedCookies(TableNode $cookiesTable)
    {
        $this->get('api')->setCookies($cookiesTable->getRowsHash());
    }

    /**
     * @Given /^I specified the following request options:?$/
     */
    public function iSpecifiedOptions(TableNode $optionsTable)
    {
        $this->get('api')->setOptions($optionsTable->getRowsHash());
    }

    /**
     * @When /^I send the request$/
     */
    public function iSendTheRequest()
    {
        $this->get('api')->send();
    }

    /**
     * @Then /^I should receive a (?<httpCode>[0-9]+) response$/
     * @Then /^I should receive a (?<httpCode>[0-9]+) (?<shortType>[a-zA-Z]+) response$/
     */
    public function iShouldReceiveResponse($httpCode, $shortType = null)
    {
        $httpCode = (int)$httpCode;

        $response = $this->get('api')->getResponse();

        $this->get('asserter')->assertEquals(
            $httpCode,
            $response->getStatusCode(),
            sprintf(
                'Expecting response code to be "%d" but "%d" given',
                $httpCode,
                $response->getStatusCode()
            )
        );

        if (null !== $shortType) {
            $contentTypes = $this->get('api')->guessType($shortType);

            foreach ($contentTypes as $contentType) {
                try {
                    $formatedContentType = explode(';', $response->getContentType());
                    $formatedContentType = $formatedContentType[0];

                    $this
                        ->get('asserter')
                        ->assertEquals($contentType, $formatedContentType)
                     ;
                    return;
                } catch (\Exception $e) {
                    continue;
                }
            }

            throw new \Exception(sprintf(
                'The response Content-Type ("%s") is not a(n) "%s" response type',
                $formatedContentType,
                $shortType
            ));
        }
    }

    /**
     * @Then /^the response should contains the following headers:?$/
     */
    public function theResponseShouldContainsHeaders(TableNode $headerTable)
    {
        $expectedHeaders = $headerTable->getRowsHash();
        $realHeaders = array_map(
            'current',
            $this->get('api')->getResponse()->getHeaders()->toArray()
        );

        $this->get('asserter')->assertArrayContains(
            $expectedHeaders,
            $realHeaders
        );
    }

    /**
     * @Then /^the response should contains the following json:?$/
     */
    public function theResponsShouldContainsJson($jsonData)
    {
        if (!is_object($jsonData)) {
            throw new \InvalidArgumentException('Invalid json data');
        }

        $json = false;

        if ($jsonData instanceof PyStringNode) {
            $json = json_decode($jsonData->getRaw(), true);
        }

        if ($jsonData instanceof TableNode) {
            $json = $jsonData->getRowsHash();
        }

        if (false === $jsonData) {
            throw new InvalidArgumentException(sprintf(
                'Invalid json data class ("%s")',
                get_class($jsonData)
            ));
        }

        $expected = json_encode($json);
        $real     = json_encode($this->get('api')->getResponse()->json());

        $this->get('asserter')->assertEquals(
            $expected,
            $real,
            sprintf("The given json\r\n\r\n%s\r\nis not equal to the expected\r\n\r\n%s",
                $real,
                $expected
            )
        );
    }

    /**
     * @Then /^the response should contains "(?<content>.+)"$/
     * @Then /^the response should contains:?$/
     */
    public function theResponseShouldContains($content = null, PyStringNode $bodyNode = null)
    {
        $content = null !== $bodyNode ? $bodyNode->getRaw() : $content;

        $this
            ->get('asserter')
            ->assertContains(
                $content,
                $this->get('api')->getResponse()->getBody(true)
            )
        ;
    }
}
