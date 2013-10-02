<?php

namespace spec\Knp\FriendlyContexts\Symfony\Profiler;

use PhpSpec\ObjectBehavior;

class SwiftMailerSpec extends ObjectBehavior
{

    /**
     * @param Symfony\Component\HttpKernel\Profiler\Profiler $profiler
     * @param Symfony\Component\HttpKernel\Profiler\Profile $profile1
     * @param Symfony\Component\HttpKernel\Profiler\Profile $profile2
     * @param Symfony\Component\HttpKernel\Profiler\Profile $profile3
     * @param Symfony\Component\HttpKernel\Profiler\Profile $profile4
     * @param Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector $swiftmailer1
     * @param Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector $swiftmailer2
     * @param Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector $swiftmailer3
     * @param Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector $swiftmailer4
     * @param Swift_Message $message1
     * @param Swift_Message $message2
     * @param Swift_Message $message3
     * @param Swift_Message $message4
     **/
    function let($profiler, $profile1, $profile2, $profile3, $profile4, $swiftmailer1, $swiftmailer2, $swiftmailer3, $swiftmailer4, $message1, $message2, $message3, $message4)
    {
        $this->beConstructedWith($profiler);

        $tokens = [
            [ 'token' => 'AZERTY' ],
            [ 'token' => 'QWERTY' ],
            [ 'token' => 'ABCDEF' ],
        ];

        $profiler->find('', '', 100, '')->willReturn($tokens);

        $profiler->loadProfile('AZERTY')->willReturn($profile1);
        $profile1->getCollector('swiftmailer')->willReturn($swiftmailer1);
        $swiftmailer1->getMessages()->willReturn([]);

        $profiler->loadProfile('QWERTY')->willReturn($profile2);
        $profile2->getCollector('swiftmailer')->willReturn($swiftmailer2);
        $swiftmailer2->getMessages()->willReturn([$message1, $message2]);

        $profiler->loadProfile('ABCDEF')->willReturn($profile3);
        $profile3->getCollector('swiftmailer')->willReturn($swiftmailer3);
        $swiftmailer3->getMessages()->willReturn([$message3]);

        $profiler->loadProfile('ZYXWVU')->willReturn($profile4);
        $profile4->getCollector('swiftmailer')->willReturn($swiftmailer4);
        $swiftmailer4->getMessages()->willReturn([$message4]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Symfony\Profiler\SwiftMailer');
    }

    function it_should_return_messages($message1, $message2, $message3)
    {
        $this->getMessages()->shouldReturn([$message1, $message2, $message3]);
    }

    function it_should_enqueue_swiftmailer()
    {
        $this->flush();

        $this->getMessages()->shouldReturn([]);
    }

    function it_should_return_new_messages($profiler, $message4)
    {
        $this->flush();

        $tokens = [
            [ 'token' => 'AZERTY' ],
            [ 'token' => 'QWERTY' ],
            [ 'token' => 'ABCDEF' ],
            [ 'token' => 'ZYXWVU' ],
        ];

        $profiler->find('', '', 100, '')->willReturn($tokens);

        $this->getMessages()->shouldReturn([$message4]);
    }
}
