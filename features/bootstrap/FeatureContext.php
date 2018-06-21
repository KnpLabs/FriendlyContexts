<?php

use Behat\Behat\Context\Context;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Behat\Gherkin\Node\PyStringNode;

class FeatureContext implements Context
{
    private static $PARAMETERS = [
        '%base_host%' => 'localhost:8123',
    ];

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

    /**
     * @var Process
     */
    private $server;

    /**
     * @var Process
     */
    private $phantomjs;

    public function __construct()
    {
        $phpFinder = new PhpExecutableFinder();
        if (false === $php = $phpFinder->find()) {
            throw new \RuntimeException('Unable to find the PHP executable. The testsuite cannot run.');
        }

        $this->phpBin = $php;
    }

    /**
     * Cleans test folders in the temporary directory.
     *
     * @BeforeSuite
     * @AfterSuite
     */
    public static function cleanTestFolders()
    {
        if (is_dir($dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'behat')) {
            self::clearDirectory($dir);
        }
        if (is_dir($dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'friendly')) {
            self::clearDirectory($dir);
        }
    }

    /**
     * Prepares test folders in the temporary directory.
     *
     * @BeforeScenario
     */
    public function prepareTestFolders()
    {
        $this->workingDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'behat' . DIRECTORY_SEPARATOR . md5(microtime() . rand(0, 10000));

        $this->createDirectory($this->workingDir . '/features/bootstrap');

        $this->process = new Process(null);
        $this->process->setTimeout(20);
    }

    /**
     * @AfterScenario
     */
    public function stopServer()
    {
        if (null !== $this->server) {
            $this->server->stop();
            $this->server = null;
        }
    }

    /**
     * @BeforeScenario @phantomjs
     */
    public function startPhantom()
    {
        if (PHP_OS === 'Darwin') {
            $phantomExec = 'phantomjs_mac';
        } else if (PHP_OS === 'Linux') {
            $phantomExec = 'phantomjs_linux';
        } else {
            throw new \Exception('This test suite cannot run on Windows, contribution in that way would be nice.');
        }
        $this->phantomjs = new Process(__DIR__ . '/../phantomjs/' . $phantomExec . ' --webdriver=4444');
        $this->phantomjs->start();
        sleep(1); // PhantomJS takes time to start
    }

    /**
     * @AfterScenario
     */
    public function stopPhantom()
    {
        if ($this->phantomjs) {
            $this->phantomjs->stop();
            $this->phantomjs = null;
        }
    }

    /**
     * Creates a file with specified name and context in current workdir.
     *
     * @Given /^(?:there is )?a file named "([^"]*)" with:$/
     *
     * @param string       $filename name of the file (relative path)
     * @param PyStringNode $content  PyString string instance
     */
    public function aFileNamedWith($filename, PyStringNode $content)
    {
        $content = strtr((string) $content, array("'''" => '"""'));
        $this->createFile($this->workingDir . '/' . $filename, $content);
    }

    private function createFile($filename, $content)
    {
        $path = dirname($filename);
        $this->createDirectory($path);

        file_put_contents($filename, $content);
    }

    private function createDirectory($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    private static function clearDirectory($path)
    {
        $files = scandir($path);
        array_shift($files);
        array_shift($files);

        foreach ($files as $file) {
            $file = $path . DIRECTORY_SEPARATOR . $file;
            if (is_dir($file)) {
                self::clearDirectory($file);
            } else {
                unlink($file);
            }
        }

        rmdir($path);
    }

    /**
     * @Given the homepage of my application is:
     */
    public function theHomepageOfMyApplicationIs(PyStringNode $string)
    {
        file_put_contents($this->workingDir . '/index.html', $string);
    }

    /**
     * @Given the file :file is:
     */
    public function theFileWith($file, PyStringNode $string)
    {
        file_put_contents($this->workingDir . DIRECTORY_SEPARATOR . $file, $string);
    }

    /**
     * @Given my application is running
     */
    public function myApplicationIsRunning()
    {
        // Using exec because of PHP limitation
        // See https://bugs.php.net/bug.php?id=39992
        $this->server = new Process('exec php -S ' . self::$PARAMETERS['%base_host%'] . ' -t ' . $this->workingDir);
        $this->server->start();
    }

    /**
     * @Given I have the following behat configuration:
     */
    public function iHaveTheFollowingBehatConfiguration(PyStringNode $string)
    {
        $string = $string->getRaw();
        $string = str_replace(array_keys(self::$PARAMETERS), array_values(self::$PARAMETERS), $string);
        file_put_contents($this->workingDir . '/behat.yml.dist', $string);
    }

    /**
     * Runs behat command with provided parameters
     *
     * @When /^I run "behat(?: ((?:\"|[^"])*))?"$/
     *
     * @param string $argumentsString
     */
    public function iRunBehat($argumentsString = '')
    {
        $argumentsString = strtr($argumentsString, array('\'' => '"'));

        $this->process->setWorkingDirectory($this->workingDir);
        $this->process->setCommandLine(
            sprintf(
                '%s %s %s',
                $this->phpBin,
                escapeshellarg(BEHAT_BIN_PATH),
                $argumentsString
            )
        );

        // Don't reset the LANG variable on HHVM, because it breaks HHVM itself
        $env = $this->process->getEnv();
        $env['LANG'] = 'en'; // Ensures that the default language is en, whatever the OS locale is.
        $this->process->setEnv($env);

        $this->process->run();
    }

    /**
     * Checks whether previously ran command passes|fails with provided output.
     *
     * @Then /^it should (fail|pass) with:$/
     *
     * @param string       $success "fail" or "pass"
     * @param PyStringNode $text    PyString text instance
     */
    public function itShouldPassWith($success, PyStringNode $text)
    {
        $this->itShouldFail($success);
        $this->theOutputShouldContain($text);
    }

    /**
     * Checks whether last command output contains provided string.
     *
     * @Then the output should contain:
     *
     * @param PyStringNode $text PyString text instance
     */
    public function theOutputShouldContain(PyStringNode $text)
    {
        expect($this->getOutput())->toContain($this->getExpectedOutput($text));
    }

    /**
     * Checks whether previously ran command failed|passed.
     *
     * @Then /^it should (fail|pass)$/
     *
     * @param string $success "fail" or "pass"
     */
    public function itShouldFail($success)
    {
        if ('fail' === $success) {
            if (0 === $this->getExitCode()) {
                echo 'Actual output:' . PHP_EOL . PHP_EOL . $this->getOutput();
            }

            expect($this->getExitCode())->toBe(0);
        } else {
            if (0 !== $this->getExitCode()) {
                echo 'Actual output:' . PHP_EOL . PHP_EOL . $this->getOutput();
            }

            expect($this->getExitCode())->toBe(0);
        }
    }

    private function getExpectedOutput(PyStringNode $expectedText)
    {
        $text = strtr($expectedText, array(
            '\'\'\'' => '"""',
            '%%TMP_DIR%%' => sys_get_temp_dir() . DIRECTORY_SEPARATOR,
            '%%WORKING_DIR%%' => realpath($this->workingDir . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR,
            '%%DS%%' => DIRECTORY_SEPARATOR,
        ));

        // windows path fix
        if ('/' !== DIRECTORY_SEPARATOR) {
            $text = preg_replace_callback(
                '/[ "]features\/[^\n "]+/', function ($matches) {
                return str_replace('/', DIRECTORY_SEPARATOR, $matches[0]);
            }, $text
            );
            $text = preg_replace_callback(
                '/\<span class\="path"\>features\/[^\<]+/', function ($matches) {
                return str_replace('/', DIRECTORY_SEPARATOR, $matches[0]);
            }, $text
            );
            $text = preg_replace_callback(
                '/\+[fd] [^ ]+/', function ($matches) {
                return str_replace('/', DIRECTORY_SEPARATOR, $matches[0]);
            }, $text
            );
        }

        return $text;
    }

    private function getExitCode()
    {
        return $this->process->getExitCode();
    }

    private function getOutput()
    {
        $output = $this->process->getErrorOutput() . $this->process->getOutput();

        // Normalize the line endings in the output
        if ("\n" !== PHP_EOL) {
            $output = str_replace(PHP_EOL, "\n", $output);
        }

        return trim(preg_replace("/ +$/m", '', $output));
    }
}
