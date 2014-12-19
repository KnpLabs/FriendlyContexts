<?php

namespace Knp\FriendlyExtension\Context;

use Behat\Gherkin\Node\TableNode;
use Knp\FriendlyExtension\Context\Context;

class SymfonyMailerContext extends Context
{
    /**
     * @Then no email should have been sent
     */
    public function noEmailShouldHaveBeenSent()
    {
        $count = $this->get('swiftmailer')->countEmailsSent();

        $this->get('asserter')->assertEquals(
            0,
            $count,
            sprintf('%d emails have been sent.', $count)
        );
    }

    /**
     * @Then email with subject :subject should have been sent
     */
    public function emailWithSubjectShouldHaveBeenSent($subject)
    {
        if (!$this->get('swiftmailer')->isEmailSent($subject)) {
            throw new \Exception(sprintf(
                'No email with subject "%s" has been sent.',
                $subject
            ));
        }
    }

    /**
     * @Then email with subject :subject should not be sent
     */
    public function emailWithSubjectShouldNotBeSent($subject)
    {
        if ($this->get('swiftmailer')->isEmailSent($subject)) {
            throw new \Exception(sprintf(
                'Email with subject "%s" have been sent.',
                $subject
            ));
        }
    }

    /**
     * @Then email should have been sent to :recipient
     */
    public function emailShouldHaveBeenSentToRecipient($recipient)
    {
        if (!$this->get('swiftmailer')->isEmailSent(null, $recipient)) {
            throw new \Exception(sprintf(
                'No email has been sent to "%s".',
                $recipient
            ));
        }
    }

    /**
     * @Then email should not be sent to :recipient
     */
    public function emailShouldNotBeSentToRecipient($recipient)
    {
        if ($this->get('swiftmailer')->isEmailSent(null, $recipient)) {
            throw new \Exception(sprintf(
                'Email have been sent to "%s".',
                $recipient
            ));
        }
    }

    /**
     * @Then email with subject :subject should have been sent to :recipient
     */
    public function emailWithSubjectShouldHaveBeenSentToRecipient($subject, $recipient)
    {
        if (!$this->get('swiftmailer')->isEmailSent($subject, $recipient)) {
            throw new \Exception(sprintf(
                'No email with subject "%s" has been sent to "%s".',
                $subject,
                $recipient
            ));
        }
    }

    /**
     * @Then email with subject :subject should not be sent to :recipient
     */
    public function emailWithSubjectShouldNotBeSentToRecipient($subject, $recipient)
    {
        if ($this->get('swiftmailer')->isEmailSent($subject, $recipient)) {
            throw new \Exception(sprintf(
                'Email with subject "%s" have been sent to "%s".',
                $subject,
                $recipient
            ));
        }
    }
}
