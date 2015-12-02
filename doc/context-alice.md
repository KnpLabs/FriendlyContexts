Alice Context
=============
This context use the [nelmio/alice](https://github.com/nelmio/alice) fixture loading system.

>**Warning** : Alice context is using nelmio/alice ~2.0 since v0.6. If you have to use Alice ~1.0, use v.0.5.


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
            - Knp\FriendlyContexts\Context\AliceContext
    extensions:
        # ...
        Knp\FriendlyContexts\Extension:
            alice:
                fixtures:
                    User: path/to/my/user/fixtures.yml
                    Product: path/to/my/product/fixtures.yml
                dependencies: ~
```

Usage
-----
Load a file

```gherkin
@alice(User) @alice(Product)
Feature: My feature
    The feature description
    ...
```

Load all files 

```gherkin
@alice(*)
Feature: My feature
    The feature description
    ...
```

Manage dependencies between alice files
---------------------------------------

If, for example, you have to load a "User" alice file before a "Product" alice file, you can do it directly from the feature : 

```gherkin
@alice(User) @alice(Product)
Feature: My feature
...
```

Or you can manage de dependence via your behat.yml
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
            alice:
                fixtures:
                    User: path/to/my/user/fixtures.yml
                    Product: path/to/my/product/fixtures.yml
                dependencies: 
                        Product: [ User ]
                        # ...
```


Entity Context
--------------

All imported fixtures can be re-used in the [Entity Context](context-entity.md) as object reference.
```yaml
# user.yml
App\Entity\User:
    user-john:
        firstname: John
        lastname: Doe
    user-admin:
        firstname: Admin
        lastname: Admin
```

```gherkin
@alice(User)
Feature: My feature
    The feature description
    
    Background:
        Given the following products
            | name  | user  |
            | Ball  | John  |
            | Shoes | Admin |
            
    ...
```

Add Alice provider
------------------

You can add a provider via a class name or a Symfony service
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
            alice:
                fixtures: #...
                dependencies: # ...
                providers:
                    - App\Alice\MyProvider # from class
                    - @app.alice.my_provider # from service
```

Information
-----------

Files will be loaded following the order of files declaration in your behat.yml file. Tags order has no impact.
