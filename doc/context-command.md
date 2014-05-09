Command Context
===============

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
            - Knp\FriendlyContexts\Context\CommandContext
```

Usage
-----
List all command
```gherkin
Scenario:
    Given show available commands
```

Prepare a command
```gherkin
Scenario:
    Given I prepare a "my:command" command
```

Build a command
```gherkin
Scenario:
    Given I add the option "first" with value "theValue"
    Given I add the argument "first" with value "theValue"
```

Execute a command
```gherkin
Scenario:
    When I run the command
```

Test the command result
```gherkin
Scenario:
    Then the command result should be:
        """
        Result as text
        """
    Then the command result should be:
        """
        Result as text with <comment>format</command>
        """
    Then the command result should contains "text with <comment>format</command>"
    Then the command result should contains "text without format"
    Then the command result code should be 0
    Then the command should succeed
    Then the command should fail
```
