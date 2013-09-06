<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Behat\Context\BehatContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class SwiftMailerContext extends RawMinkContext implements KernelAwareInterface
{
    use \Behat\Symfony2Extension\Context\KernelDictionary;
    use \Knp\FriendlyContexts\Dictionary\Symfony;
    use \Knp\FriendlyContexts\Dictionary\Contextable;

    protected $swiftmailer;
    protected $tokens = [];
    protected $messages = [];

    /**
     * @BeforeScenario
     */
    public function initContext($event)
    {
        $this->registerTokens();
    }

    /**
     * @Then /^(\d+) mail should have been sent$/
     * @Then /^(\d+) mails should have been sent$/
     */
    public function mailShouldBeSent($expected)
    {
        $this->registerMessages();

        $real = array_sum(
            array_map(
                function ($e) {
                    return count($e->getTo()) + count($e->getCc()) + count($e->getBcc());
                },
                $this->messages
            )
        );

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

    protected function getWatchableTokens()
    {
        $tokens = array_map(
            function ($e) {
                return $e['token'];
            },
            $this->getProfiler()->find('', '', 100, '')
        );

        return array_diff($tokens, $this->tokens);
    }

    protected function registerTokens()
    {
        $this->tokens = array_merge($this->tokens, $this->getWatchableTokens());
    }

    protected function registerMessages()
    {
        $tokens = $this->getWatchableTokens();
        $this->messages = [];

        foreach ($tokens as $token) {
            $swiftmailer = $this->getProfiler()->loadProfile($token)->getCollector('swiftmailer');

            $this->messages = array_merge($this->messages, $swiftmailer->getMessages());
        }
    }
}
