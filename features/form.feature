@javascript
Feature: I am able to use a form

    Background:
        Given I am on the form page
        And I wait

    Scenario Outline: I fill in text fields
        When I fill in the <number> "<field>" field with "<value>"
        Then I should see "<result>"

        Examples:
            | number | field      | value | result                            |
            | 2nd    | Text field | foo   | Field Text field (text-2) value updated |
            | last   | Text field | bar   | Field Text field (text-4) value updated |

    Scenario Outline: I check radio with same names
        When I check the <number> "<field>" radio
        Then I should see "<result>"

        Examples:
            | number | field         | result                                           |
            | first  | Radio field 1 | Field Radio field 1 (first-radio) value updated  |
            | last   | Radio field 1 | Field Radio field 1 (second-radio) value updated |
            | 2nd    | Radio field 1 | Field Radio field 1 (second-radio) value updated |
