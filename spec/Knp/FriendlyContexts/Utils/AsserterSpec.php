<?php

namespace spec\Knp\FriendlyContexts\Utils;

use Knp\FriendlyContexts\Utils\TextFormater;
use PhpSpec\ObjectBehavior;

class AsserterSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new TextFormater);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Utils\Asserter');
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
        $expected = "The given array\r\n\r\n| 10 | test | 1 |\r\nis not equals to expected\r\n\r\n| 10 | text |  |";

        $this->shouldThrow(new \Exception($expected, 1))->duringAssertArrayEquals([ 10, 'text', false ], [ 10, 'test', true ]);
    }

    function it_assert_that_an_array_contains_an_other()
    {
        $expected = "The given array\r\n\r\n| 10 | test | 1 |\r\ndoes not contain the following rows\r\n\r\n| 10 | text |  |";

        $this->shouldThrow(new \Exception($expected, 1))->duringAssertArrayContains([ 10, 'text', false ], [ 10, 'test', true ]);
    }

    function it_detect_array_into_table()
    {
        $real = [
            [ 1, 2, 3, 4 ],
            [ 'a', 'b', 'c', 'd' ],
            [ 'd', 'b', 'a', 'b' ],
            [ 1, 2, 3, 4 ],
        ];

        $expected = [
            [ 'b', 'a' ],
            [ 2, 3 ],
        ];

        $this->assertArrayContains($expected, $real)->shouldReturn(true);

        $expected = [
            [ 2, 3 ],
            [ 'b', 'c' ],
        ];

        $this->assertArrayContains($expected, $real)->shouldReturn(true);
    }

    function it_throw_when_array_doesnt_contain()
    {
        $real = [
            [ 1, 2, 3, 4 ],
            [ 'a', 'b', 'c', 'd' ],
            [ 'd', 'b', 'a', 'b' ],
            [ 1, 2, 3, 4 ],
        ];

        $expected = [
            [ 1, 2 ],
            [ 'a', 'd' ],
        ];

        $this->shouldThrow()->duringAssertArrayContains($expected, $real);
    }
}
