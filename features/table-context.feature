Feature: Table Context
  In order to use the table context
  As a developer
  I need to be able to add it to my configuration

  Scenario: I test a table in an HTML page scenario
    Given the homepage of my application is:
      """
        <!DOCTYPE html>
        <html>
          <head></head>
          <body>
            <table>
              <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Login</th>
              </tr>
              <tr>
                <td>John</td>
                <td>Doe</td>
                <td>JD</td>
              </tr>
              <tr>
                <td>George</td>
                <td>Abitbol</td>
                <td>GA</td>
              </tr>
              <tr>
                <td>Bob</td>
                <td>Sponge</td>
                <td>BS</td>
              </tr>
            </table>
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
              - Behat\MinkExtension\Context\MinkContext: ~
              - Knp\FriendlyContexts\Context\TableContext: ~
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
      Feature: homepage with table

        Scenario:
          Given I am on "/index.html"
          Then I should see the following table
            | Firstname | Lastname | Login |
            | John      | Doe      | JD    |
            | George    | Abitbol  | GA    |
            | Bob       | Sponge   | BS    |
          And I should see the following table
            | Firstname | Lastname |
            | John      | Doe      |
            | George    | Abitbol  |
            | Bob       | Sponge   |
          Then I should see the following table portion
            | Firstname | Lastname |
            | John      | Doe      |
            | George    | Abitbol  |
          And I should see a table
          And I should see a table with 4 rows
          And I should see a table with "John, George and Bob" in the "Firstname" column
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      .......

      1 scenario (1 passed)
      7 steps (7 passed)
      """
