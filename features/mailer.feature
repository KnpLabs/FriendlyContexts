Feature: I am able to describe sending emails

    Scenario: No emails sent
        When I go to the noEmails page
        Then no email should have been sent

    Scenario: Email with subject sent
        When I go to the email page
        Then email with subject "Hello Subject" should have been sent
         And email with subject "Not sent" should not be sent

    Scenario: Email sent to recipeient
        When I go to the email page
        Then email should have been sent to "recipient@example.com"
        Then email should not be sent to "umpirsky@gmail.com"
