<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use Faker\Generator;
use Faker\Provider\File;
use Faker\Provider\Person;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileSpec extends ObjectBehavior
{
    function let(Generator $generator, File $file)
    {
        $this->beConstructedWith($generator);
        $this->setParent($file);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\File');
    }

    function it_should_return_parent_provider($file)
    {
        $this->getParent()->shouldReturn($file);
    }

    function it_should_supports_File_original_provider($file)
    {
        $this->supportsParent($file)->shouldReturn(true);
    }

    function it_should_not_supports_non_File_original_provider(Person $person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
