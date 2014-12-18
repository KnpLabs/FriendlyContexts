Feature: I am able to describe sending emails

    Scenario: No emails sent
        When I go to the noEmails page
        Then no email should have been sent

    Scenario: Email with subject sent
        When I go to the emailWithSubject page
        Then email with subject "Hello Subject" should have been sent
