Feature: I am able to use a form

    Background:
        Given I am on the form page

    @javascript
    Scenario Outline: I fill in text fields
        When I fill in the <number> "<field>" field with "<value>"
        And I wait
        Then I should see "<result>"

        Examples:
            | number | field      | value | result                                 |
            | first  | Text field | foo   | Field Text field (text-0) value is foo |
            | last   | Text field | bar   | Field Text field (text-4) value is bar |
