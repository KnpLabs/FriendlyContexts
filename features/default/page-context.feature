Feature: Page Context
  In order to use the page context
  As a developer
  I need to be able to add it to my configuration

  Background:
    Given I have the following behat configuration:
      """
      default:
        autoload:
          "Page": "%paths.base%/features" # configure your page autoload
        extensions:
          Behat\MinkExtension:
            base_url:  'http://%base_host%/'
            sessions:
              default:
                goutte: ~
          Knp\FriendlyContexts\Extension:
            page:
              namespace: "Page"
        suites:
          default:
            contexts:
              - Behat\MinkExtension\Context\MinkContext: ~
              - Knp\FriendlyContexts\Context\PageContext: ~
      """

  Scenario: I test with "I am on the * page" scenario
    And the file "features/Page/PlopPage.php" is:
      """
      <?php
      namespace Page;

      use Knp\FriendlyContexts\Page\Page;

      class PlopPage extends Page
      {
        public function getPath()
        {
          return '/plop/';
        }
      }
      """
    And the file "plop/index.htm" is:
      """
        <!DOCTYPE html>
        <html>
          <head></head>
          <body>
            <h1>Plop</h1>
          </body>
        </html>
      """
    And my application is running
    And a file named "features/plop.feature" with:
      """
      Feature: plop
        In order to access the plop
        As a standard visitor
        I need to navigate to the plop

        Scenario:
          Given I am on the plop page
          Then I should see "Plop"
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ..

      1 scenario (1 passed)
      2 steps (2 passed)
      """

  Scenario: I test with "I am on the * page with:" scenario
    And the file "features/Page/PlopPage.php" is:
      """
      <?php
      namespace Page;

      use Knp\FriendlyContexts\Page\Page;

      class PlopPage extends Page
      {
        public function getPath()
        {
          return '/plop/{argument1}/{argument2}';
        }
      }
      """
    And the file "plop/foo/bar/index.htm" is:
      """
        <!DOCTYPE html>
        <html>
          <head></head>
          <body>
            <h1>Bar</h1>
          </body>
        </html>
      """
    And my application is running
    And a file named "features/bar.feature" with:
      """
      Feature: plop
        In order to access the plop
        As a standard visitor
        I need to navigate to the bar

        Scenario:
          Given I am on the plop page with:
            | argument1 | foo |
            | argument2 | bar |
          Then I should see "Bar"
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ..

      1 scenario (1 passed)
      2 steps (2 passed)
      """

