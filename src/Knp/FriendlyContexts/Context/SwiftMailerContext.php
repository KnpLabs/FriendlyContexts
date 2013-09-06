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
     * @Then /^(\d+) mail should be sent$/
     * @Then /^(\d+) mails should be sent$/
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
