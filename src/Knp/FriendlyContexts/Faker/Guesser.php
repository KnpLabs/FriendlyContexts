<?php

namespace Knp\FriendlyContexts\Faker;

use Faker\Factory;
use Knp\FriendlyContexts\Dictionary\Containable;
use Faker\Provider\Base;

class Guesser
{
    use Containable;

    protected $faker;
    protected $providers = [];

    public function __construct()
    {
        $this->faker = Factory::create();

        $providers = [
            new Provider\Address($this->faker),
            new Provider\Color($this->faker),
            new Provider\Company($this->faker),
            new Provider\DateTime($this->faker),
            new Provider\File($this->faker),
            new Provider\Internet($this->faker),
            new Provider\Lorem($this->faker),
            new Provider\Miscellaneous($this->faker),
            new Provider\Payment($this->faker),
            new Provider\Person($this->faker),
            new Provider\PhoneNumber($this->faker),
            new Provider\UserAgent($this->faker),
            new Provider\Uuid($this->faker),
        ];

        foreach ($providers as $provider) {
            $this->providers[$provider->getName()] = $provider;
        }

        foreach ($this->faker->getProviders() as $provider) {
            $this->registerFakerProvider($provider);
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

    protected function registerFakerProvider(Base $fakerProvider)
    {
        foreach ($this->providers as $provider) {
            if ($provider->supportsParentProvider($fakerProvider)) {
                $provider->setParentProvider($fakerProvider);
            }
        }
    }
}
