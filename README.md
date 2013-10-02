KnpLabs - FriendlyContexts
================

[![Build Status](https://travis-ci.org/PedroTroller/FriendlyContexts.png)](https://travis-ci.org/PedroTroller/FriendlyContexts)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/030bad5b-c724-457c-9fd3-da63c8416364/mini.png)](https://insight.sensiolabs.com/projects/030bad5b-c724-457c-9fd3-da63c8416364)

**Doctrine entities generation context**

```php
// FeatureContext.php
// ...
use Knp/FriendlyContexts/Context/EntityContext;

class FeatureContext extends MinkContext
{
    public function __construct($options)
    {
        // ...
        $this->useContext('entity', new EntityContext($options));
        // ...
    }
}
```
```gherkin
Feature: Comment sending
    In order to send comments
    As a user
    I should be able to send comments by email

    Background:
        Given the following users
            | username  | firstname | lastname | email                                    |
            | j.doe     | John      | DOE      | j.doe@the.unknow.com                     |
            | g.abitbol | George    | ABITBOL  | g.abitbol@classiest.man.in.the.world.com |
        And the following news
            | title          | content     | writer |
            | The first news | The content | j.do   |
        And I am logged as "g.abitbol"
        And I am on the homepage

    Scenario: Successfully list all news
        Given I should see "The first news"
```
