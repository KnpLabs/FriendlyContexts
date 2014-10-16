Feature: I am able to navigate between pages

    @smart-step
    Scenario: I open a page
        When I go to the table page
        Then I should be on the table page

    @smart-step
    Scenario: I change the page
        Given I open a page
        When I go to the index page
        Then I should be on the index page

    Scenario: I come bak to the first page
        Given I change the page
        When I go to the table page
        Then I should be on the table page

    Scenario: I should be on a compite page
        Given I am on the a page with:
            | part      | html  |
            | file      | index |
            | extension | html  |
        When I go to the table page
        Then I should be on the a page with:
            | part      | html   |
            | file      | table1 |
            | extension | html   |
