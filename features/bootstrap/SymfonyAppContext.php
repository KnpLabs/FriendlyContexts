<?php

use Behat\Behat\Context\Context;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Behat\Gherkin\Node\PyStringNode;

class SymfonyAppContext extends AbstractContext
{
    /**
     * SymfonyAppContext constructor.
     */
    public function __construct()
    {
        $this->behatProcessTimeout = 60;
        parent::__construct();
    }

    /**
     * @BeforeSuite
     */
    public static function setWorkingDirectory()
    {
        self::$PARAMETERS['%working_dir%'] = realpath(__DIR__ . '/../../testapp');
    }

    /**
     * @BeforeScenario
     */
    public function cleanTestApp()
    {
        $bundlePath = self::$PARAMETERS['%working_dir%'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "Knp" . DIRECTORY_SEPARATOR . "FcTestBundle";
        self::$filesystem->remove(array(
            self::$PARAMETERS['%working_dir%'] . DIRECTORY_SEPARATOR . "features" . DIRECTORY_SEPARATOR . "fixtures",
            $bundlePath . DIRECTORY_SEPARATOR . "Controller",
            $bundlePath . DIRECTORY_SEPARATOR . "Entity",
            $bundlePath . DIRECTORY_SEPARATOR . "Resources",
            $bundlePath . DIRECTORY_SEPARATOR . "Features",
        ));
    }
}
