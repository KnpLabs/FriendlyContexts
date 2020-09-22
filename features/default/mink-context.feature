Feature: Mink Context
  In order to use the mink context
  As a developer
  I need to be able to add it to my configuration

  Scenario: I test with "I press" scenario
    Given the homepage of my application is:
      """
        <!DOCTYPE html>
        <html>
          <head></head>
          <body>
            <h1>Hello</h1>
            <p><a href="foo.html">foo</a></p>
          </body>
        </html>
      """
    And the file "foo.html" is:
      """
        World
      """
    And my application is running
    And I have the following behat configuration:
      """
      default:
        suites:
          default:
            contexts:
              - Knp\FriendlyContexts\Context\MinkContext: ~
        extensions:
          Behat\MinkExtension:
            base_url:  'http://%base_host%/'
            sessions:
              default:
                goutte: ~
          Knp\FriendlyContexts\Extension: ~
      """
    And a file named "features/homepage.feature" with:
      """
      Feature: homepage
        In order to access the homepage
        As a standard visitor
        I need to navigate to the homepage

        Scenario:
          Given I am on the homepage
          When I press the "foo" link
          Then I should see "World"
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ...

      1 scenario (1 passed)
      3 steps (3 passed)
      """

  Scenario: I test the third link "foo"
    Given the homepage of my application is:
      """
        <!DOCTYPE html>
        <html>
          <head></head>
          <body>
            <h1>Hello</h1>
            <p><a href="bar.html">foo</a></p>
            <p><a href="http://schema.org">foo</a></p>
            <p><a href="foo.html">foo</a></p>
            <p><a href="http://wikipedia.org">foo</a></p>
          </body>
        </html>
      """
    And the file "foo.html" is:
      """
        World
      """
    And my application is running
    And I have the following behat configuration:
      """
      default:
        suites:
          default:
            contexts:
              - Knp\FriendlyContexts\Context\MinkContext: ~
        extensions:
          Behat\MinkExtension:
            base_url:  'http://%base_host%/'
            sessions:
              default:
                goutte: ~
          Knp\FriendlyContexts\Extension: ~
      """
    And a file named "features/homepage.feature" with:
      """
      Feature: test
        Scenario:
          Given I am on the homepage
          When I follow the 3rd "foo" link
          Then I should see "World"
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ...

      1 scenario (1 passed)
      3 steps (3 passed)
      """

  Scenario: test label enumeration scenario
    Given the homepage of my application is:
      """
        <!DOCTYPE html>
        <html>
          <head></head>
          <body>
            <h1>Hello</h1>
            <form method="POST" action="foo.php">
              <label for="foo1">Foo</label>
              <input type="text" id="foo1" name="foo1" />
              <label for="foo2">Foo</label>
              <input type="text"  id="foo2" name="foo2" />
              <button type="submit">Go!</button>
            </form>
          </body>
        </html>
      """
    And the file "foo.php" is:
      """
        <ul>
          <?php
          foreach($_POST as $name => $value) {
            echo "<li>$name : $value</li>";
          }
          ?>
        </ul>
      """
    And my application is running
    And I have the following behat configuration:
      """
      default:
        suites:
          default:
            contexts:
              - Knp\FriendlyContexts\Context\MinkContext: ~
        extensions:
          Behat\MinkExtension:
            base_url:  'http://%base_host%/'
            sessions:
              default:
                goutte: ~
          Knp\FriendlyContexts\Extension: ~
      """
    And a file named "features/homepage.feature" with:
      """
      Feature: test
        Scenario:
          Given I am on the homepage
          And I fill in the first "Foo" field with "Hello"
          And I fill in the 2nd "Foo" field with "World"
          When I press the "Go!" button
          Then I should see "Hello"
          And I should see "World"
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ......

      1 scenario (1 passed)
      6 steps (6 passed)
      """

  Scenario: test checkbox enumeration
    Given the homepage of my application is:
      """
        <!DOCTYPE html>
        <html>
          <head></head>
          <body>
            <h1>Hello</h1>
            <form method="POST" action="foo.php">
              <p>choose what you love</p>
              <input type="checkbox" id="q1_pizza" name="q1_pizza" value="on" />
              <label for="q1_pizza">pizza</label>
              <input type="checkbox" id="q1_fish" name="q1_fish" value="on" />
              <label for="q1_fish">fish</label>

              <p>choose what you hate</p>
              <input type="checkbox" id="q2_pizza" name="q2_pizza" value="on" />
              <label for="q2_pizza">pizza</label>
              <input type="checkbox" id="q2_fish" name="q2_fish" value="on" />
              <label for="q2_fish">fish</label>

              <button type="submit">send</button>
            </form>
          </body>
        </html>
      """
    And the file "foo.php" is:
      """
        <ul>
          <?php
          foreach($_POST as $name => $value) {
            echo "<li>$name : $value</li>";
          }
          ?>
        </ul>
      """
    And my application is running
    And I have the following behat configuration:
      """
      default:
        suites:
          default:
            contexts:
              - Knp\FriendlyContexts\Context\MinkContext: ~
        extensions:
          Behat\MinkExtension:
            base_url:  'http://%base_host%/'
            sessions:
              default:
                goutte: ~
          Knp\FriendlyContexts\Extension: ~
      """
    And a file named "features/homepage.feature" with:
      """
      Feature: test
        Scenario:
          Given I am on the homepage
          And I check the first "fish" checkbox
          And I check the 2nd "pizza" checkbox
          When I press the "send" button
          Then I should see "q1_fish : on"
          And I should see "q2_pizza : on"
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ......

      1 scenario (1 passed)
      6 steps (6 passed)
      """

  @phantomjs
  Scenario: test radio enumeration
    Given the homepage of my application is:
      """
        <!DOCTYPE html>
        <html>
          <head></head>
          <body>
            <h1>Hello</h1>
            <form method="POST" action="foo.php">
              <p>Hello</p>
                <input type="radio" name="hello" id="yes_hello" value="yes" /> <label for="yes_hello">Yes</label>
                <input type="radio" name="hello" id="no_hello" value="no" /> <label for="no_hello">No</label>
              <p>World</p>
                <input type="radio" name="world" id="yes_world" value="yes" /> <label for="yes_world">Yes</label>
                <input type="radio" name="world" id="no_world" value="no" /> <label for="no_world">No</label>

              <button type="submit">send</button>
            </form>
          </body>
        </html>
      """
    And the file "foo.php" is:
      """
        <ul>
          <?php
          foreach($_POST as $name => $value) {
            echo "<li>$name : $value</li>";
          }
          ?>
        </ul>
      """
    And my application is running
    And I have the following behat configuration:
      """
      default:
        suites:
          default:
            contexts:
              - Knp\FriendlyContexts\Context\MinkContext: ~
        extensions:
          Behat\MinkExtension:
            base_url:  'http://%base_host%/'
            sessions:
              default:
                selenium2: ~
          Knp\FriendlyContexts\Extension: ~
      """
    And a file named "features/homepage.feature" with:
      """
      Feature: test
        Scenario:
          Given I am on the homepage
          And I check the first "Yes" radio
          And I check the 2nd "Yes" radio
          When I press the "send" button
          Then I should see "hello : yes"
          And I should see "world : yes"
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ......

      1 scenario (1 passed)
      6 steps (6 passed)
      """

  Scenario: test vision of enumerated button
    Given the homepage of my application is:
      """
        <!DOCTYPE html>
        <html>
          <head></head>
          <body>
            <h1>Hello</h1>
            <button>World</button>
            <button>World</button>
            <button>World</button>
          </body>
        </html>
      """
    And my application is running
    And I have the following behat configuration:
      """
      default:
        suites:
          default:
            contexts:
              - Knp\FriendlyContexts\Context\MinkContext: ~
        extensions:
          Behat\MinkExtension:
            base_url:  'http://%base_host%/'
            sessions:
              default:
                goutte: ~
          Knp\FriendlyContexts\Extension: ~
      """
    And a file named "features/homepage.feature" with:
      """
      Feature: test
        Scenario:
          Given I am on the homepage
          Then I should see 3 "World" button
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ..

      1 scenario (1 passed)
      2 steps (2 passed)
      """

  Scenario: vision of checkbox
    Given the homepage of my application is:
      """
        <!DOCTYPE html>
        <html>
          <head></head>
          <body>
            <h1>Hello</h1>
            <input type="checkbox" name="hello" id="yes" value="yes" /> <label for="yes"> Agree</label>

          </body>
        </html>
      """
    And my application is running
    And I have the following behat configuration:
      """
      default:
        suites:
          default:
            contexts:
              - Knp\FriendlyContexts\Context\MinkContext: ~
        extensions:
          Behat\MinkExtension:
            base_url:  'http://%base_host%/'
            sessions:
              default:
                goutte: ~
          Knp\FriendlyContexts\Extension: ~
      """
    And a file named "features/homepage.feature" with:
      """
      Feature: test
        Scenario:
          Given I am on the homepage
          Then I should see a "Agree" checkbox
          And I should not see a "Agree" button
          And I should not see a "Agree" radio
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ....

      1 scenario (1 passed)
      4 steps (4 passed)
      """
