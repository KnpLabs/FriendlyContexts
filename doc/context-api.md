Api Context
===========

This context allow you to handle web Api features for your applications. It needs some configuration:

```yaml
default:
    extensions:
        Knp\FriendlyContexts\Extension:
            api:
                base_url: 'http://your-site-to-test/'
```

## Configuration

Edit behat.yml:

```yaml
default:
    # ...
    suites:
        default:
            # ...
        contexts:
            - # ...
            - Knp\FriendlyContexts\Context\ApiContext
    extensions:
        # ...
        Knp\FriendlyContexts\Extension: 
            api:
                base_url: http://0.0.0.0:8080/
```

## Create a request

You can create a request with the following steps:

```gherkin
Given I prepare a GET request on "/users" # prepare a get request on '/user'
Given I prepare a POST request on the user create page # prepare a POST request on the user create page (see Page context)
Given I prepare a PUT request on the /users resource # Like the first one but a little more verbose
```

## Pass request parameters

You can pass a full set of parameters into your created request with the following steps

```gherkin
# Specified some headers
Given I specified the following request headers:
    | Accept | text/html |
# Specified queries
Given I specified the following request queries:
    | q | my search field |
# Specified request data (like POST or PUT data)
Given I specified the following request data:
    | my_form[name] | George ABITBOL |
# Specified request files for POST requests
    | fileName | path/to/the/file |
# Specified cookies
Given I specified the following request cookies:
    | my_option | some data here |
# Specified request options
Given I specified the following request options:
    | my_option | some data here |
```

## Send the request

To send the request just use the following step:

```gherkin
When I send the request
```

## Test the response

To test the request response you have the choice between all this steps:

```gherkin
# Assert the response and content type
Then I should receive a 200 response # Assert that the response code is 200
Then I should receive a 200 json response # Assert that the response  code is 2OO and the content type is a valid json

# assert headers, and data content
Then the response should contain the following headers:
    | Cache-Control | max-age=21600 |
Then the response should contain the following json:
    """
    {
        "plop": {
            "plip": 13,
            "foo": "bar"
        }
    }
    """
Then the response should contain:
    """
    <?xml version="1.0" encoding="UTF-8"?>
    <some-data>
        <plop>
            <plip type="integer">13</plip>
            <foo>bar</foo>
        </plop>
    </some-data>
    """
```

## Handle security

Actually the friendly context can handle two kind of http security:

- Http basic
- OAuth 1

To create a secured request, just use the following steps:

```gherkin
# Http basic
Given I specified the following request http basic credentials:
    | username | john@doe.com |
    | password | johnpass     |
# Oauth 1
Given I specified the following request oauth credentials:
    | consumer_key    | my_key          |
    | consumer_secret | my_secret       |
    | token           | my_token        |
    | token_secret    | my_secret_token |
```

Note that you can write a short content-type syntax according to the [matching table](../src/Knp/FriendlyContexts/Http/HttpContentTypeGuesser.php).


