Api Context
===========

This context allow you to handle web Api features for your applications.

## Create a request

You can create a request with the following steps:

```gherkin
Given I prepare a GET request on "/users" # prepare a get request on '/user'
Given I prepare a POST request on the user create page # prepare a POST request on the user create page (see Page context)
Given I prepare a PUT request on the /users resource # Like the first one but a little more verbose
```

## Pass request parameters

You can pass a full set of parameters into your created request with the follwing steps

```gherkin
# Precised some headers
Given I precised the following request headers:
    | Accept | text/html |
# Precised queries
Given I presiced the following request queries:
    | q | my search field |
# Precised request data (like POST or PUT data)
Given I precised the following request data:
    | my_form[name] | George ABITBOL |
# Precised request options
Given I precised the following request options:
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
Then the response should contains the following headers:
    | Cache-Control | max-age=21600 |
Then the response should contains the following json:
    """
    {
        "plop": {
            "plip": 13,
            "foo": "bar"
        }
    }
    """
Then the response should contains:
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

Note that you can write a short content-type syntax according to the [matching table](../src/Knp/FriendlyContexts/Http/HttpContentTypeGuesser.php).


