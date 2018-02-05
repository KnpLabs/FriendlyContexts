<?php

namespace spec\Knp\FriendlyContexts\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\Type;
use Knp\FriendlyContexts\Doctrine\EntityResolver;
use Knp\FriendlyContexts\Guesser\GuesserManager;
use Knp\FriendlyContexts\Guesser\GuesserInterface;
use Knp\FriendlyContexts\Utils\TextFormater;
use Knp\FriendlyContexts\Utils\UniqueCache;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntityHydratorSpec extends ObjectBehavior
{
    /**
     * @param Knp\FriendlyContexts\Utils\TextFormater      $formatter
     * @param Knp\FriendlyContexts\Guesser\GuesserManager  $manager
     * @param Knp\FriendlyContexts\Doctrine\EntityResolver $resolver
     * @param Knp\FriendlyContexts\Utils\UniqueCache       $uniqueCache
     **/
    public function let(TextFormater $formatter, GuesserManager $manager, EntityResolver $resolver, UniqueCache $uniqueCache)
    {
        $this->beConstructedWith($formatter, $manager, $resolver, $uniqueCache);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Doctrine\EntityHydrator');
    }

    /**
     * Current design of EntityHydrator::hydrate doesn't allow meaningful testing with PHPSpec
     */
    public function it_hydrates_simple_columns(ObjectManager $objectManager, GuesserInterface $guesser, ScalarValuesEntity $e, $manager, $resolver, $formatter)
    {
        $entity = new ScalarValuesEntity();
        $mapping = ['fieldName' => 'foo', 'type' => Type::STRING];

        $guesser->transform('bar', $mapping)->willReturn('bar');
        $formatter->toCamelCase('foo')->willReturn('foo');

        $manager->find(Argument::any())
            ->willReturn($guesser);
        $resolver->getMetadataFromProperty($objectManager, $entity, 'foo')
            ->willReturn($mapping);

        $this->hydrate($objectManager, $entity, ['foo' => 'bar']);
    }
}

class ScalarValuesEntity
{
    private $foo;

    public function getFoo()
    {
        return $this->foo;
    }

    public function setFoo($foo)
    {
        $this->foo = $foo;
    }
}