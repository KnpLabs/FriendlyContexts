<?php

namespace spec\Knp\FriendlyContexts\Tool;

use PhpSpec\ObjectBehavior;
use Knp\FriendlyContexts\Tool\TextFormater;

class AsserterSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new TextFormater);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Tool\Asserter');
    }

    function it_should_assert_if_equals()
    {
        $object = new \StdClass;

        $this->assertEquals(true, true)->shouldReturn(true);
        $this->assertEquals(0, 0)->shouldReturn(true);
        $this->assertEquals(1000, 1000)->shouldReturn(true);
        $this->assertEquals('string', "string")->shouldReturn(true);
        $this->assertEquals(null, null)->shouldReturn(true);
        $this->assertEquals($object, $object)->shouldReturn(true);
        $this->assertEquals([ 0, 1, 2, 3 ], [ 0, 1, 2, 3 ])->shouldReturn(true);
    }

    function it_should_throw_and_exception_when_can_t_assert()
    {
        $this->shouldThrow(new \Exception("Failing to assert equals.", 1))->duringAssertEquals(true, false);
        $this->shouldThrow(new \Exception("Failing to assert equals.", 1))->duringAssertEquals(0, 1);
        $this->shouldThrow(new \Exception("Failing to assert equals.", 1))->duringAssertEquals("string", "STRING");
        $this->shouldThrow(new \Exception("Failing to assert equals.", 1))->duringAssertEquals(0, "0");
        $this->shouldThrow(new \Exception("Failing to assert equals.", 1))->duringAssertEquals(new \StdClass, new \StdClass);
    }

    function it_should_display_the_array_when_display_the_error()
    {
        $expected = "The given array\r\n\r\n| 10   |\r\n| test |\r\n| 1    |\r\n\r\nis not equals to expected\r\n\r\n| 10   |\r\n| text |\r\n|      |\r\n";

        $this->shouldThrow(new \Exception($expected, 1))->duringAssertArrayEquals([ 10, 'text', false ], [ 10, 'test', true ]);
    }
}
