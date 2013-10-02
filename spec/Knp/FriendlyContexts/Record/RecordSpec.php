<?php

namespace spec\Knp\FriendlyContexts\Record;

use PhpSpec\ObjectBehavior;

use Knp\FriendlyContexts\Reflection\ObjectReflector;
use Knp\FriendlyContexts\Record\Collection;

class RecordSpec extends ObjectBehavior
{
    /**
     * @param StdClass $rightObject
     * @param StdClass $wrongObject
     * @param Knp\FriendlyContexts\Reflection\ObjectReflector $reflector
     * @param Knp\FriendlyContexts\Record\Collection $collection
     **/
    function let(\StdClass $rightObject, \StdClass $wrongObject, ObjectReflector $reflector, Collection $collection)
    {
        $reflector->getClassName($rightObject)->willReturn('TheClass');
        $reflector->getClassNamespace($rightObject)->willReturn('The\\Name\\Space');

        $reflector->getClassName($wrongObject)->willReturn('TheOtherClass');
        $reflector->getClassNamespace($wrongObject)->willReturn('The\\Other\\Name\\Space');

        $collection->support($rightObject)->willReturn(true);
        $collection->support($wrongObject)->willReturn(false);

        $this->beConstructedWith($reflector, $collection);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Record\Record');
    }

    function it_should_attach_an_object_if_supported(\StdClass $rightObject)
    {
        $this->attach($rightObject)->shouldReturn(null);
        $this->getEntity()->shouldReturn($rightObject);
    }

    function it_should_throw_an_exception_if_entity_not_supported(\StdClass $wrongObject)
    {
        $this->shouldThrow(new \InvalidArgumentException('Given entity is not supported by the collection'))->duringAttach($wrongObject);
        $this->getEntity()->shouldReturn(null);
    }

    function it_should_attach_an_entity_with_raw_values(\StdClass $rightObject)
    {
        $values = [ 'name' => 'MyName', 'id' => 'MyId' ];

        $this->attach($rightObject, $values)->shouldReturn(null);
        $this->all()->shouldReturn($values);
    }

    function it_should_return_true_if_record_has_raw_value(\StdClass $rightObject)
    {
        $values = [ 'name' => 'MyName', 'id' => 'MyId' ];

        $this->attach($rightObject, $values)->shouldReturn(null);
        $this->has('name')->shouldReturn(true);
    }

    function it_should_return_false_if_record_dont_has_raw_value(\StdClass $rightObject)
    {
        $values = [ 'name' => 'MyName', 'id' => 'MyId' ];

        $this->attach($rightObject, $values)->shouldReturn(null);
        $this->has('price')->shouldReturn(false);
    }

    function it_should_return_guessed_element_if_record_has_raw_value(\StdClass $rightObject)
    {
        $values = [ 'name' => 'MyName', 'id' => 'MyId' ];

        $this->attach($rightObject, $values)->shouldReturn(null);
        $this->get('name')->shouldReturn('MyName');
        $this->get('id')->shouldReturn('MyId');
    }

    function it_should_return_null_if_record_dont_has_raw_value(\StdClass $rightObject)
    {
        $values = [ 'name' => 'MyName', 'id' => 'MyId' ];

        $this->attach($rightObject, $values)->shouldReturn(null);
        $this->get('name')->shouldReturn('MyName');
        $this->get('id')->shouldReturn('MyId');
    }

    function it_should_return_true_if_given_raw_field_exists_and_value_is_equals(\StdClass $rightObject)
    {
        $values = [ 'name' => 'MyName', 'id' => 'MyId' ];

        $this->attach($rightObject, $values)->shouldReturn(null);
        $this->equals('name', 'MyName')->shouldReturn(true);
    }

    function it_should_return_false_if_given_raw_field_exists_and_value_not_is_equals(\StdClass $rightObject)
    {
        $values = [ 'name' => 'MyName', 'id' => 'MyId' ];

        $this->attach($rightObject, $values)->shouldReturn(null);
        $this->equals('name', 'MyFirstName')->shouldReturn(false);
    }

    function it_should_return_false_if_given_raw_field_dont_exists(\StdClass $rightObject)
    {
        $values = [ 'name' => 'MyName', 'id' => 'MyId' ];

        $this->attach($rightObject, $values)->shouldReturn(null);
        $this->equals('firstname', 'MyName')->shouldReturn(false);
    }
}
