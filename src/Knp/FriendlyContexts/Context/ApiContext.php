<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use Guzzle\Http\Exception\BadResponseException;

class ApiContext extends RawPageContext
{
    protected $response;

    /**
     * @Given /^I prepare a (?<method>[A-Za-z]+) request on "(?<page>[^"].*)"?$/
     * @Given /^I prepare a (?<method>[A-Za-z]+) request on the (.*) (?<hasPage>page|resource)$/
     * @Given /^I prepare a (?<method>[A-Za-z]+) request on the (.*) (?<hasPage>page|resource) with:?$/
     */
    public function iPrepareRequest($method, $page, $hasPage = false, TableNode $table = null)
    {
        $hasPage = (bool)$hasPage;
        $method  = strtoupper($method);

        $this->getRequestBuilder()->setMethod($method);

        if ($hasPage) {
            $path = $this->getPagePath($page, $table);
        } else {
            $path = $page;
        }

        $this->getRequestBuilder()->setUri($path);
    }

    /**
     * @Given /^I specified the following request http basic credentials:?$/
     */
    public function iSpecifiedTheFollowingBasicAuthentication(TableNode $credentialsTable)
    {
        $this
            ->getRequestBuilder()
            ->setCredentials($credentialsTable->getRowsHash())
            ->addSecurityExtension(new HttpBasicExtension)
        ;
    }

    /**
     * @Given /^I specified the following request oauth credentials:?$/
     */
    public function iSpecifiedTheFollowingOauthCredentials(TableNode $credentialsTable)
    {
        $this
            ->getRequestBuilder()
            ->setCredentials($credentialsTable->getRowsHash())
            ->addSecurityExtension(new OauthExtension)
        ;
    }

    /**
     * @Given /^I specified the following request headers:?$/
     */
    public function iSpecifiedHeaders(TableNode $table)
    {
        $this->getRequestBuilder()->setHeaders($table->getRowsHash());
    }

    /**
     * @Given /^I specified the following request queries:?$/
     */
    public function iSpecifiedQueries(TableNode $table)
    {
        $this->getRequestBuilder()->setQueries($table->getRowsHash());
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

        $this->getRequestBuilder()->setBody($data);
    }

    /**
     * @Given /^I specified the following request data:?$/
     */
    public function iSpecifiedData(TableNode $dataTable)
    {
        $requestBuilder = $this->getRequestBuilder();

        if ('POST' === $requestBuilder->getMethod()) {
            $requestBuilder->setPostBody($dataTable->getRowsHash());
        } else {
            $requestBuilder->setBody($dataTable->getRowsHash());
        }
    }

    /**
     * @Given /^I specified the following request cookies:?$/
     */
    public function iSpecifiedCookies(TableNode $cookiesTable)
    {
        $this->getRequestBuilder()->setCookies($cookiesTable->getRowsHash());
    }

    /**
     * @Given /^I specified the following request options:?$/
     */
    public function iSpecifiedOptions(TableNode $optionsTable)
    {
        $this->getRequestBuilder->setOptions($optionsTable->getRowsHash());
    }

    /**
     * @When /^I send the request$/
     */
    public function iSendTheRequest()
    {
        try {
            $this->response = $this->getRequestBuilder()->build()->send();
        } catch (BadResponseException $e) {
            $this->response = $e->getResponse();
        }
    }

    /**
     * @Then /^I should receive a (?<httpCode>[0-9]+) response$/
     * @Then /^I should receive a (?<httpCode>[0-9]+) (?<shortType>[a-zA-Z]+) response$/
     */
    public function iShouldReceiveResponse($httpCode, $shortType = null)
    {
        $httpCode = (int)$httpCode;

        if (null === $this->response) {
            throw new \RuntimeException('You must send a request before testing a response.');
        }

        $this->getAsserter()->assertEquals(
            $httpCode,
            $this->response->getStatusCode(),
            sprintf(
                'Expecting response code to be "%d" but "%d" given',
                $httpCode,
                $this->response->getStatusCode()
            )
        );

        if (null !== $shortType) {
            $contentTypes = $this->getHttpContentTypeGuesser()->guess($shortType);

            foreach ($contentTypes as $contentType) {
                try {
                    $formatedContentType = explode(';', $this->response->getContentType());
                    $formatedContentType = $formatedContentType[0];

                    $this
                        ->getAsserter()
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
        if (null === $this->response) {
            throw new \RuntimeException('You must send a request before testing a response.');
        }

        $expectedHeaders = $headerTable->getRowsHash();
        $this->getAsserter()->assertArrayContains(
            $expectedHeaders,
            $this->response->getHeaders()
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

        $this->getAsserter()->assertArrayEquals($json, $this->response->json());
    }

    /**
     * @Then /^the response should contains:?$/
     */
    public function theResponseShouldContains(PyStringNode $bodyNode)
    {
        $this->getAsserter()->assertEquals($bodyNode->getRaw(), $this->response->getBody(true));
    }
}
