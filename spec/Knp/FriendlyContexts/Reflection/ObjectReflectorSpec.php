<?php

namespace spec\Knp\FriendlyContexts\Reflection;

use PhpSpec\ObjectBehavior;

class ObjectReflectorSpec extends ObjectBehavior
{
    /**
     * @param StdClass $object
     **/
    function let(\StdClass $object)
    {
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Reflection\ObjectReflector');
    }

    function it_should_return_the_object_reflection_class(\StdClass $object)
    {
        $this->getReflectionClass($object)->shouldHaveType('ReflectionClass');
    }

    function it_should_return_object_class_name(\StdClass $object)
    {
        $this->getClassName($object)->shouldBeString();
    }

    function it_should_return_object_class_namespace(\StdClass $object)
    {
        $this->getClassNamespace($object)->shouldBeString();
    }

    function it_should_return_object_class_long_name(\StdClass $object)
    {
        $this->getClassLongName($object)->shouldBeString();
    }
}
