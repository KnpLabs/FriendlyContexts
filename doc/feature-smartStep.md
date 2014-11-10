Smart Step Feature
=============

Configuration
-------------
Edit behat.yml
```yaml
default:
    # ...
    suites:
        default:
            # ...
        contexts:
            - # ...
    extensions:
        # ...
        Knp\FriendlyContexts\Extension:
            smartTag: smartStep # <= default option
```

Usage
-----
```gherkin
Feature: My feature
    
    @smartStep
    Scenario: The first scenario
        Given the first step
        When the second step
        Then the third step
        
    Scenario: the second scenario
        Given The first scenario
```
You can reuse a scenario tagged with **@smartStep** (or your custom tag) as a step.

Example
-------
I want to describe a multi-step form
```gherkin
# features/login.feature
Feature: Login
    
    @smartStep
    Scenario: I am logged in as "admin"
        Given I am on the login page
        When I fill in "Login" with "admin"
        And I fill in "Password" with "admin"
        And I press "Submit"
        Then I should not see "Bad credentials"
```
```gherkin
# features/form.feature
Feature: Fill in the multistep form
    
    Background:
        Given I am logged in as "admin" # <= reused scenario
        
    @smartStep
    Scenario: I fill in the first step
        Given I am on the form page
        When I fill in "field1" with "value1"
        And I press "Submit"
        Then I should be on the step 2 page
        
    @smartStep
    Scenario: I fill in the second step
        Given I fill in the first step # <= reused scenario
        When I fill in "field2" with "value2"
        And I press "Submit"
        Then I should be on the step 3 page
        
    Scenario: I fill in the third step
        Given I fill in the second step # <= reused scenario
        When I fill in "field3" with "value3"
        And I press "Submit"
        Then I should be on the product page
```
