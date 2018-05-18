<?php

use Behat\Behat\Context\Context;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class FeatureContext implements Context
{
    /**
     * @var string
     */
    private $workingDir;

    /**
     * @var string
     */
    private $phpBin;

    /**
     * @var Process
     */
    private $process;

    public function __construct()
    {
        $phpFinder = new PhpExecutableFinder();
        if (false === $php = $phpFinder->find()) {
            throw new \RuntimeException('Unable to find the PHP executable. The testsuite cannot run.');
        }

        $this->phpBin = $php;
    }

    /**
     * Prepares test folders in the temporary directory.
     *
     * @BeforeScenario
     */
    public function prepareTestFolders()
    {
        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'behat' . DIRECTORY_SEPARATOR . md5(microtime() . rand(0, 10000));

        mkdir($dir . '/features/bootstrap', 0777, true);

        $this->workingDir = $dir;
        $this->process = new Process(null);
        $this->process->setTimeout(20);
    }
}
