KnpLabs - FriendlyContexts
================

[![Build Status](https://travis-ci.org/KnpLabs/FriendlyContexts.png?branch=master)](https://travis-ci.org/KnpLabs/FriendlyContexts)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/KnpLabs/FriendlyContexts/badges/quality-score.png?s=5292581c45ba61ea028dfb54c21c2ba50df604a2)](https://scrutinizer-ci.com/g/KnpLabs/FriendlyContexts/)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5620dc28-b1bb-43b0-be73-5c032d363fd7/mini.png)](https://insight.sensiolabs.com/projects/5620dc28-b1bb-43b0-be73-5c032d363fd7)
[![Latest Stable Version](https://poser.pugx.org/knplabs/friendly-contexts/v/stable.png)](https://packagist.org/packages/knplabs/friendly-contexts)
[![Latest Unstable Version](https://poser.pugx.org/knplabs/friendly-contexts/v/unstable.png)](https://packagist.org/packages/knplabs/friendly-contexts)

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
