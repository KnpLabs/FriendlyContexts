Page Context
=============

The page context allow you to quickly configure and manipulate application
page.

Configuration
-------------
In your `behat.yml`

```yaml
default:
    autoload:
        "Page": "%paths.base%/features" # configure your page autoload
    suite:
        default:
            # ...
        contexts:
            - # ...
            - Knp\FriendlyContexts\Context\PageContext
    extensions:
        # ...
        Knp\FriendlyContexts\Extension:
            page:
                namespace: "Page" # the namespace by default for page object
```

Usage
-----
First start by creating a page object

```php
<?php // features/Page/PlopPage.php

namespace Page;

use Knp\FriendlyContexts\Page\Page;

class PlopPage extends Page
{
    public function getPath()
    {
        return '/plop/';
    }
}
```

You can precised a suit of arguments

```php
public function getPath()
{
    return '/plop/{argument1}/{argument2}';
}
```

Available step
--------------

The following steps are available

```gherkin
Given I am on the plop page
Given I am on the plop page with:
    | argument1 | foo |
    | argument2 | bar |
When I go to the plop page
When I go to the plop page with:
    | argument1 | foo |
    | argument2 | bar |
Then I should be on the plop page
Then I should be on the plop page with:
    | argument1 | foo |
    | argument2 | bar |
```

Nested entities
---------------

The page context can resolved a page for a given entity:

```php
<?php // features/Page/EditEntityPage.php

namespace Page;

use Knp\FriendlyContexts\Page\Page;

class EditEntityPage extends Page
{
    public function getPath()
    {
        return '/entities/{entity.id}/edit';
    }
}
```

You can resolved it with the following step:

```gherkin
Given the following entities
    | id | name |
    | 10 | foo  |
    | 11 | bar  |
When I am on the edit entity page with:
    | entity | the entity "bar" |
```

The page path will be `/entities/11/edit`.

Use pages in a context
----------------------

A page object is a simple [DocumentElement](https://github.com/Behat/Mink/blob/master/src/Behat/Mink/Element/DocumentElement.php). 
Let's write the given page:

```php
<?php // features/Page/EditEntityPage.php

namespace Page;

use Knp\FriendlyContexts\Page\Page;

class EditEntityPage extends Page
{
    public function getPath()
    {
        return '/entities/{entity.id}/edit';
    }

    public function fillForm($name)
    {
        $this->fillField('entity[name]', $name);
    }
}
```

You can use it in your context by extends the following class:

```php
<?php // features/Context/MyContext.php

namespace Page;

use Knp\FriendlyContexts\Context\RawPageContext;

class MyContext extends RawPageContext
{
    /**
     * @Given /^I edit the entity with "([^"]+)"$/
     */
    public function iEditAnEntityWith($name)
    {
        $this
            ->getPage('edit entity')
            ->fillForm($name)
        ;
    }
}
```
