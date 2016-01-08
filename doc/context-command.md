Command Context
===============

**This context requires [`symfony/framework-bundle`](https://packagist.org/packages/symfony/framework-bundle) ~2.3|~3.0
dependency.**

Configuration
-------------
Edit behat.yml:

```yaml
default:
    # ...
    suites:
        default:
            # ...
        contexts:
            - # ...
            - Knp\FriendlyContexts\Context\CommandContext
    extensions:
        # ...
        Knp\FriendlyContexts\Extension: ~
```

Examples
--------
This context allows you to run any Symfony command from your project:

```gherkin
  When I run acme:demo:my_command
```

**I want to ensure command has been successfully executed**

Once this command executed, you can test its exit code:

```gherkin
  Then command should be successfully executed
```

Or if you want to be more precise:

```gherkin
  Then command exit code should be 0
```

**I want to test command output**

Command output can be partially tested, this is useful is you don't care about output format:

```gherkin
  And command output should be like:
  """
  [OK] My command has been successfully executed
  """
```

**What if my command should throw an exception ?**

Sometimes, you want to manage an exception while executing your command. There are 2 ways to test it:
you can test if an exception is thrown:

```gherkin
  And command should throw an exception
```

or you can also test exception output:

```gherkin
  And command should throw following exception:
  """
  You are not allowed to execute this command.
  """
```
