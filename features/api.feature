Feature: I am able to describe API

    Scenario: I send a get request
        Given I prepare a GET request on "/api"
        When I send the request
        Then the response should contains the following headers:
            | Content-type | text/html |
        And I should receive a 200 response
        And the response should contains:
        """
            <body>
                <h1>This is a test</h1>
            </body>
        """
        And the response should contains "This is a test"

    Scenario: I send a put request
        Given I prepare a PUT request on "/api"
        When I send the request
        Then I should receive a 404 response

    Scenario: I send a get request and recieved a json
        Given I prepare a GET request on "/api"
        Given I specified the following request headers:
            | format | json |
        When I send the request
        Then the response should contains the following headers:
            | Content-type | application/json |
        And I should receive a 200 response
        Then the response should contains the following json:
        """
        {
            "plop": {
                "plip": 13,
                "foo": "bar"
            }
        }
        """

    Scenario: I send a post request and recieved a json
        Given I prepare a POST request on "/api"
        Given I specified the following request headers:
            | format | json |
        And I specified the following request data:
            | id | 17 |
        When I send the request
        Then the response should contains the following headers:
            | Content-type | application/json |
        And I should receive a 202 response
        Then the response should contains the following json:
        """
        {
            "plop": {
                "plip": 17,
                "foo": "bar"
            }
        }
        """
