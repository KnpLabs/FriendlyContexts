<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use Guzzle\Http\Exception\BadResponseException;

class ApiContext extends RawPageContext
{
    private $response;

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
     * @Given /^I precised the following request headers:?$/
     */
    public function iPrecisedHeaders(TableNode $table)
    {
        $this->getRequestBuilder()->setHeaders($table->getRowsHash());
    }

    /**
     * @Given /^I precised the following request queries:?$/
     */
    public function iPrecisedQueries(TableNode $table)
    {
        $this->getRequestBuilder()->setQueries($table->getRowsHash());
    }

    /**
     * @Given /^I precised the following request body:?$/
     */
    public function iPrecisedTheBody($data)
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
     * @Given /^I precised the following request data:?$/
     */
    public function iPrecisedData(TableNode $dataTable)
    {
        $requestBuilder = $this->getRequestBuilder();

        if ('POST' === $requestBuilder->getMethod()) {
            $requestBuilder->setPostBody($dataTable->getRowsHash());
        } else {
            $requestBuilder->setBody($dataTable->getRowsHash());
        }
    }

    /**
     * @Given /^I precised the following request options:?$/
     */
    public function iPrecisedOptions(TableNode $optionsTable)
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
}
