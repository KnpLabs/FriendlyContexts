Mink Context
============
This context is just an extension of the original mink context from the [Behat Mink Extension](http://extensions.behat.org/mink/)

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
            - Knp\FriendlyContexts\Context\MinkContext
    extensions:
        # ...
        Knp\FriendlyContexts\Extension: ~
```

Examples
--------

```gherkin
  When I press the "Download" button
  And I press the "Download" link
  And I press the 1st "Download" button
  And I press the 2nd "Download" link
  And I press the 3rd "Download" button
  And I press the 4th "Download" link 
  # ...
  And I fill in the first "Input label" field with "Value"
  And I fill in the 1st "Input label" field with "Value"
  And I fill in the 2nd "Input label" field with "Value"
  And I fill in the 3rd "Input label" field with "Value"
  # ...
  And I check the "Agree" checkbox
  And I check the 1st "Agree" checkbox
  And I check the 2nd "Agree" radio
  Then I should see 5 "Delete" button
  And I should not see 5 "Update" link
  And I should see a "Agree" checkbox
  And I should not see a "Agree" radio
```
