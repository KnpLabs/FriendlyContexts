<?php

namespace Knp\FriendlyContexts\Context;

use Knp\FriendlyContexts\Symfony\Profiler\SwiftMailer;

class SwiftMailerContext extends RawMinkContext
{
    protected $profiler;
    protected $messages;

    /**
     * @BeforeScenario
     */
    public function initContext($event)
    {
        $this->profiler = $this->profiler ?: new SwiftMailer($this->getProfiler());
        $this->profiler->flush();

        $this->messages = [];
    }

    /**
     * @Then /^(\d+) mail should have been sent$/
     * @Then /^(\d+) mails should have been sent$/
     */
    public function mailShouldBeSent($expected)
    {
        $real = array_sum(
            array_map(
                function ($e) {
                    return count($e->getTo()) + count($e->getCc()) + count($e->getBcc());
                },
                $this->messages = $this->profiler->getMessages()
            )
        );

        $this->profiler->flush();

        $this->assertEquals(
            (int) $expected,
            (int) $real,
            sprintf('fail to assert %s mail(s) sent, %s sent in reality.', $expected, $real)
        );
    }

    /**
     * @Given /^an email with subject "([^"]*)" should have been sent to "([^"]*)"$/
     */
    public function anEmailWithSubjectShouldHaveBeenSentTo($subject, $email)
    {
        $messages = array_filter(
            $this->messages,
            function ($e) use ($subject) {
                return false !== strpos(strtoupper($e->getSubject()), strtoupper($subject));
            }
        );

        if (0 === count($messages)) {
            throw new \Exception(sprintf(
                'Can\'t find email with subject containing "%s", "%s" found',
                $subject,
                implode(
                    '" or "',
                    array_map(
                        function ($e) {
                            return $e->getSubject();
                        },
                        $this->messages
                    )
                )
            ));
        }

        $emails = [];

        foreach ($messages as $message) {
            $emails = array_merge($emails, $message->getTo());
            $emails = array_merge($emails, $message->getCc());
            $emails = array_merge($emails, $message->getBcc());
        }

        $emails = array_keys($emails);

        if (!in_array($email, $emails)) {
            throw new \Exception(
                sprintf(
                    'The message with the subject "%s" has not been sent to "%s", found recipients are "%s"',
                    $subject,
                    $email,
                    implode(
                        '" and "',
                        $emails
                    )
                )
            );
        }
    }
}
