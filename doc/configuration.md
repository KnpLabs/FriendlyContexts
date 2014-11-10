Installation
============

Execute

```
execute : composer require knplabs/friendly-contexts dev-master
```

Edit behat.yml
```yaml
default:
    # ...
    suites:
        default:
            # ...
        contexts:
            - # ...
            - Knp\FriendlyContexts\Context\MinkContext
            - Knp\FriendlyContexts\Context\TableContext
            - Knp\FriendlyContexts\Context\EntityContext
    extensions:
        # ...
        Knp\FriendlyContexts\Extension: ~
```

