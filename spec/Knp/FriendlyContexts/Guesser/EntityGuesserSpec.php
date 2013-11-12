<?php

namespace spec\Knp\FriendlyContexts\Guesser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntityGuesserSpec extends ObjectBehavior
{
    /**
     * @param Knp\FriendlyContexts\Record\Collection\Bag $bag
     * @param Knp\FriendlyContexts\Record\Collection $collection
     * @param Knp\FriendlyContexts\Record\Record $record
     **/
    function let($bag, $collection, $record, $entity)
    {
        $this->beConstructedWith($bag);

        $bag->getCollection("App\Entity\User")->willReturn($collection);
        $record->getEntity()->willReturn($entity);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Guesser\EntityGuesser');
    }

    function it_should_supports_known_entity($bag, $collection)
    {
        $mapping = [
            'fieldName'    => "created_by",
            'unique'       => false,
            'nullable'     => false,
            'columnName'   => "created_by",
            'targetEntity' => "App\Entity\User",
        ];

        $bag->getCollection("App\Entity\User")->willReturn($collection);
        $collection->count()->willReturn(1);

        $this->supports($mapping)->shouldReturn(true);
    }

    function it_should_not_supports_known_entity_but_there_is_no_record($collection)
    {
        $mapping = [
            'fieldName'    => "created_by",
            'unique'       => false,
            'nullable'     => false,
            'columnName'   => "created_by",
            'targetEntity' => "App\Entity\User",
        ];

        $collection->count()->willReturn(0);

        $this->supports($mapping)->shouldReturn(false);
    }

    function it_should_not_supports_if_mapping_unsupported()
    {
        $mapping = [
            'fieldName'  => "created_at",
            'type'       => "datetime",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "created_at",
        ];

        $this->supports($mapping)->shouldReturn(false);
    }

    function it_should_transform_a_string_to_a_record($collection, $record, $entity)
    {
        $mapping = [
            'fieldName'    => "created_by",
            'unique'       => false,
            'nullable'     => false,
            'columnName'   => "created_by",
            'targetEntity' => "App\Entity\User",
        ];

        $collection->search('user1')->willReturn($record);

        $this->transform('user1', $mapping)->shouldReturn($entity);
    }

    function it_should_return_null_when_transformation_inpossible()
    {
        $mapping = [
            'fieldName'    => "created_by",
            'unique'       => false,
            'nullable'     => false,
            'columnName'   => "created_by",
            'targetEntity' => "App\Entity\User",
        ];

        $this->transform('user2', $mapping)->shouldReturn(null);
    }
}
