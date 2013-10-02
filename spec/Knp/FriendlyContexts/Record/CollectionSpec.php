<?php

namespace spec\Knp\FriendlyContexts\Record;

use PhpSpec\ObjectBehavior;

use Knp\FriendlyContexts\Reflection\ObjectReflector;

class CollectionSpec extends ObjectBehavior
{
    /**
     * @param StdClass $rightObject
     * @param StdClass $wrongObject
     * @param Knp\FriendlyContexts\Reflection\ObjectReflector $reflector
     **/
    function let(\StdClass $rightObject, \StdClass $wrongObject, ObjectReflector $reflector)
    {
        $reflector->getClassName($rightObject)->willReturn('TheClass');
        $reflector->getClassNamespace($rightObject)->willReturn('The\\Name\\Space');
        $reflector->getClassLongName($rightObject)->willReturn('The\\Name\\Space\\TheClass');
        $reflector->isInstanceOf($rightObject, 'The\\Name\\Space\\TheClass')->willReturn(true);

        $reflector->getClassName($wrongObject)->willReturn('TheOtherClass');
        $reflector->getClassNamespace($wrongObject)->willReturn('The\\Other\\Name\\Space');
        $reflector->getClassLongName($wrongObject)->willReturn('The\\Other\\Name\\Space\\TheOtherClass');
        $reflector->isInstanceOf($wrongObject, 'The\\Name\\Space\\TheClass')->willReturn(false);

        $this->beConstructedWith($reflector);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Record\Collection');
    }

    function it_should_support_entity_if_not_initialized(\StdClass $rightObject)
    {
        $this->support($rightObject)->shouldReturn(true);
        $this->getReferencial()->shouldReturn('The\Name\Space\TheClass');
    }

    function it_should_set_the_referencial_from_string()
    {
        $str = "The\\Name\\Space\Of\\The\\Class";

        $this->setReferencial($str)->shouldReturn($this);
        $this->getReferencial()->shouldReturn($str);
    }

    function it_should_set_the_referencial_from_entity(\StdClass $rightObject)
    {
        $this->setReferencial($rightObject)->shouldReturn($this);
        $this->getReferencial()->shouldReturn('The\Name\Space\TheClass');
    }

    function it_should_support_entity_when_corresponding_to_string(\StdClass $rightObject)
    {
        $str = "The\\Name\\Space\\TheClass";

        $this->setReferencial($str)->shouldReturn($this);
        $this->support($rightObject)->shouldReturn(true);
    }

    function it_should_support_entity_when_corresponding_to_object(\StdClass $rightObject)
    {
        $this->setReferencial($rightObject)->shouldReturn($this);
        $this->support($rightObject)->shouldReturn(true);
    }

    function it_should_not_support_entity_when_not_corresponding_to_string(\StdClass $wrongObject)
    {
        $str = "The\\Name\\Space\\TheClass";

        $this->setReferencial($str)->shouldReturn($this);
        $this->support($wrongObject)->shouldReturn(false);
    }

    function it_should_not_support_entity_when_not_corresponding_to_object(\StdClass $rightObject, \StdClass $wrongObject)
    {
        $this->setReferencial($rightObject)->shouldReturn($this);
        $this->support($wrongObject)->shouldReturn(false);
    }
}
