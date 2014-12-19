Feature: I am able to describe sending emails

    Scenario: No emails sent
        When I go to the noEmails page
        Then no email should have been sent
         And 0 emails should have been sent

    Scenario: Email with subject sent
        When I go to the email page
        Then email with subject "Hello Subject" should have been sent
        And 1 emails should have been sent

     Scenario: Email with subject not sent
        When I go to the email page
        And email with subject "Not sent" should not be sent

    Scenario: Email sent to recipeient
        When I go to the email page
        Then email should have been sent to "recipient@example.com"

    Scenario: Email not sent to recipeient
        When I go to the email page
        Then email should not be sent to "umpirsky@gmail.com"

    Scenario: Email with subject not sent to recipeient
        When I go to the email page
        Then email with subject "Not sent" should not be sent to "recipient@example.com"
         And email with subject "Hello Subject" should not be sent to "umpirsky@gmail.com"
         And email with subject "Not sent" should not be sent to "umpirsky@gmail.com"
