Installation
============

Execute

```
composer require knplabs/friendly-contexts dev-master
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

### Kernel configuration

If you have some fancy kernel configuration, you can just set it like this (`shown values are default`):

```yaml
default:
    # ...
    extensions:
        # ...
        Knp\FriendlyContexts\Extension:
            symfony_kernel:
                bootstrap: app/autoload.php
                path: app/AppKernel.php
                class: AppKernel
                env: test
                debug: true
```

### Doctrine configuration

The Alice and Entity contexts make use of Doctrine.

You can choose the name of the Doctrine service in Symfony that will be used.
This allows you to use MongoDB for the Alice context:

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
            # ...
            doctrine:
                service: 'doctrine_mongodb'
```

The default value is `doctrine`.
