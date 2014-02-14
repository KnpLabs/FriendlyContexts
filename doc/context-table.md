Table Context
=============
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
            - Knp\FriendlyContexts\Context\TableContext
    extensions:
        # ...
        Knp\FriendlyContexts\Extension: ~
```
Examples
--------
With this HTML table

| Firstname | Lastname | Login |
|-----------|----------|-------|
| John      | Doe      | JD    |
| George    | Abitbol  | GA    |
| Bob       | Sponge   | BS    |

I can use the following steps

```gherkin
  Then I should see the following table
    | Firstname | Lastname | Login |
    | John      | Doe      | JD    |
    | George    | Abitbol  | GA    |
    | Bob       | Sponge   | BS    |
  And I should see the following table # I can use just a part of the table
    | Firstname | Lastname |
    | John      | Doe      |
    | George    | Abitbol  |
    | Bob       | Sponge   |
  And I should see the following table # I can use just a part of the table
    | Firstname | Login |
    | John      | JD    |
    | George    | GA    |
    | Bob       | BS    |
  And I should see a table
  And I should see a table with 3 rows
  And I should see a table with "John, George and Bob" in the column named "Firstname"
  ```
  
Information
-----------
  
  If there is multiple tables in the same page, the steps will throw an error if no table will validate the assertion. If one does, then the step will be green.
