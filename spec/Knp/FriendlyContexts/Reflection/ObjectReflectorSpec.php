<?php

namespace spec\Knp\FriendlyContexts\Reflection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectReflectorSpec extends ObjectBehavior
{
    /**
     * @param StdClass $object
     **/
    function let($object)
    {
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Reflection\ObjectReflector');
    }

    function it_should_return_the_object_reflection_class($object)
    {
        $this->getReflectionClass($object)->shouldHaveType('ReflectionClass');
    }

    function it_should_return_object_class_name($object)
    {
        $this->getClassName($object)->shouldBeString();
    }

    function it_should_return_object_class_namespace($object)
    {
        $this->getClassNamespace($object)->shouldBeString();
    }

    function it_should_return_object_class_long_name($object)
    {
        $this->getClassLongName($object)->shouldBeString();
    }
}
