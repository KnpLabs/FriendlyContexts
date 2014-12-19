<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle,
            new Symfony\Bundle\TwigBundle\TwigBundle,
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle,
            new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle,
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle,
            new App\App
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function($container) {
            $container->loadFromExtension('framework', array(
                'router' => array('resource' => __DIR__.'/App/Resources/config/routing.yml'),
                'templating' => array(
                    'engines' => array('twig'),
                ),
                'profiler' => array('enabled' => true),
            ));

            $container->loadFromExtension('doctrine', array(
                'orm' => array(),
                'dbal' => array(),
            ));

            $container->loadFromExtension('swiftmailer', array(
                'disable_delivery' => true,
            ));
        });
    }

    protected function getKernelParameters()
    {
        $parameters = parent::getKernelParameters();
        $parameters['kernel.secret'] = 'secret!';

        return $parameters;
    }

    public function getCacheDir()
    {
        return $this->rootDir.'/tmp/cache/'.$this->name.$this->environment;
    }

    public function getLogDir()
    {
        return $this->rootDir.'/tmp/logs';
    }
}
