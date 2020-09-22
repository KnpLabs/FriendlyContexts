<?php

use Behat\Behat\Context\Context;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Behat\Gherkin\Node\PyStringNode;

class FeatureContext extends AbstractContext
{
    /**
     * Cleans test folders in the temporary directory.
     *
     * @BeforeSuite
     * @AfterSuite
     */
    public static function cleanTestFolders()
    {
        if (null === self::$filesystem) {
            self::$filesystem = new Filesystem();
        }

        if (is_dir($dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'behat')) {
            self::$filesystem->remove($dir);
        }
        if (is_dir($dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'friendly')) {
            self::$filesystem->remove($dir);
        }
    }

    /**
     * Prepares test folders in the temporary directory.
     *
     * @BeforeScenario
     */
    public function prepareTestFolders()
    {
        self::$PARAMETERS['%working_dir%'] = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'behat' . DIRECTORY_SEPARATOR . md5(microtime() . rand(0, 10000));

        $this->createDirectory(self::$PARAMETERS['%working_dir%'] . '/features/bootstrap');
    }

    /**
     * @Given the homepage of my application is:
     */
    public function theHomepageOfMyApplicationIs(PyStringNode $string)
    {
        file_put_contents(self::$PARAMETERS['%working_dir%'] . '/index.html', $string);
    }
}
