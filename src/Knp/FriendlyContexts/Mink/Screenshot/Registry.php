<?php

namespace Knp\FriendlyContexts\Mink\Screenshot;

class Registry
{
    protected $builders = [];

    public function addBuilder(Builder $builder)
    {
        $this->builders[] = $builder;
    }

    public function screenshot(Session $session)
    {
        foreach ($this->builders as $builder) {
            if (false === $data = $builder->buildScreenshot($session)) {

                return new Screenshot($data, $builder->getFormat());
            }
        }

        return false;
    }
}
