Feature: command context

    Scenario: Show me commands
        Given show available commands
        And I prepare a "behat" command
        When I run the command
        Then show command result
