Entity Context
==============

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
            - Knp\FriendlyContexts\Context\EntityContext
    extensions:
        # ...
        Knp\FriendlyContexts\Extension: ~
```

### Define namespaces

It's possible to define the namespace of the entities you wish to use with the 
following  optional configuration:

```yaml
default:
    # ...
    extensions:
        # ...
        Knp\FriendlyContexts\Extension:
            entities:
                namespaces:
                    - Acme
```

Examples
--------
You have the following models (with getters and setters):

```php
<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User
{

    /**
     * @ORM\Column(type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column
     */
    private $login;

    /**
     * @ORM\Column
     */
    private $firstname;

    /**
     * @ORM\Column
     */
    private $lastname;

    /**
     * @ORM\Column
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="user")
     */
    private $products;
}
```

```php
<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Product
{

    /**
     * @ORM\Column(type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column
     */
    private $name;

    /**
     * @ORM\Column(type="decimal")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     */
    private $user;
}
```

**Simply create 2 user instances**

```gherkin
  Given the following users:
    | login | firstname | lastname |
    | admin | John      | Doe      |
    | user  | George    | Abitbol  |
```

Here the context will automatically resolve **user** to **App\Entity\User**, will create 2 instances of this entity with the given data and will fake the required **email** field.

**Simply create 3 products attached to users**

```gherkin
  And the following products:
    | name    | user  |
    | Ball    | John  |
    | T-Shirt | user  |
    | Truck   | admin |
```

Here the context will automatically resolve **products** to **App\Entity\Product**, will create 3 instances of this entity following given data and will fake the required **price** field.

Attaching a user to a product can be done by referencing a user with any information given in the previous step (login, firstname or lastname) or by the result of the *__toString* method of the user entity.

**I don't care about data, I just want 100 users**

No problem

```gherkin
  And there are 100 users
```

And now you've got 100 totally faked users. You can't call these users like in the previous example though.

**Okay, I care about data, I want to create 200 products for John Doe**

Here we go !!!

```gherkin
  And there is 200 products like:
    | user     |
    | John Doe |
```

**I want to know if a user is created/deleted**

Just ask
```gherkin
  When I open the form
  And I fill in the form
  And I press "Submit"
  Then I should see "Your user is created"
  And 1 user should have been created # <= this is the step
```

Same thing for deletion
```gherkin
  When press "Delete"
  Then I should see "Your user is deleted"
  And 1 user should have been deleted # <= this is the step
```

**Sometimes you may want to check that the correct data for a created or modified user was stored in the database

Check it by doing
```gherkin
  When I open the form
  And I fill in the form with "Albert" "Einstein" "Scientist" values
  And I press "Submit"
  Then should be 1 user like:
    | firstname | lastname | profession |
    | Albert    | Einstein | Scientist  |
```

Reset Schema
------------
You just have to use the tag **@reset-schema**
```gherkin
@reset-schema
Feature: My feature
...
```

Information
-----------------
The context can resolve a same entity class by many names. For example, if you have a class names **User**, you can use **user**, **User**, **users** or **Users**. And for a class named **ProjectGroup**, you can use **project group**, **project groups**, **projectgroup**, **projectgroups**, ...
