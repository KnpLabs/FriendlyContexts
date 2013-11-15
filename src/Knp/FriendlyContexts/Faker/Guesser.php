<?php

namespace Knp\FriendlyContexts\Faker;

use Faker\Factory;
use Knp\FriendlyContexts\Dictionary\Containable;
use Faker\Provider\Base;

class Guesser
{
    use Containable;

    protected $generator;
    protected $bases = [];
    protected $providers = [];

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;

        foreach ($this->generator->getProviders() as $provider) {
            $this->bases[] = $provider;
        }
    }

    public function getProvider($name)
    {
        if (false !== array_key_exists($name, $this->providers)) {
            return $this->providers[$name];
        }

        throw new \Exception(
            sprintf(
                'There is no provider named "%s", "%s" availables',
                $name,
                implode('", "', array_keys($this->providers))
            )
        );
    }

    public function addProvider(Base $provider)
    {
        $this->provider[$provider->getName()] = $provider;

        foreach ($this->bases as $base) {
            if ($provider->supportsParentProvider($base)) {
                $provider->setParentProvider($base);
            }
        }
    }
}
