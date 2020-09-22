<?php

use Behat\Behat\Context\Context;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Behat\Gherkin\Node\PyStringNode;

class AbstractContext implements Context
{
    protected static $PARAMETERS = [
        '%base_host%' => 'localhost:8123',
        '%working_dir%' => null, // set by @beforeScenario
    ];

    /**
     * @var Filesystem
     */
    protected static $filesystem = null;

    /**
     * @var string
     */
    private $phpBin;

    /**
     * @var Process
     */
    protected $behatProcess;

    /**
     * @var int
     */
    protected $behatProcessTimeout = 20;

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
            throw new RuntimeException('Unable to find the PHP executable. The testsuite cannot run.');
        }
        $this->phpBin = $php;
    }

    /**
     * @BeforeScenario
     */
    public function prepareBehatProcess()
    {
        $this->behatProcess = new Process(null);
        $this->behatProcess->setTimeout($this->behatProcessTimeout);
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
        switch(PHP_OS) {
            case 'Darwin':
                $phantomExec = 'phantomjs_mac';
                break;
            case 'Linux':
                $phantomExec = 'phantomjs_linux';
                break;
            default:
                throw new Exception('This test suite cannot run on Windows, contribution in that way would be nice.');
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
        $this->createFile(self::$PARAMETERS['%working_dir%'] . DIRECTORY_SEPARATOR . $filename, $content);
    }

    protected function createFile($filename, $content)
    {
        $path = dirname($filename);
        $this->createDirectory($path);

        file_put_contents($filename, $content);
    }

    protected function createDirectory($path)
    {
        self::$filesystem->mkdir($path, 0777);
    }

    /**
     * @Given the file :file is:
     */
    public function theFileWith($file, PyStringNode $string)
    {
        self::$filesystem->dumpFile(self::$PARAMETERS['%working_dir%'] . DIRECTORY_SEPARATOR . $file, $string);
    }

    /**
     * @Given my application is running
     */
    public function myApplicationIsRunning()
    {
        // Using exec because of PHP limitation
        // See https://bugs.php.net/bug.php?id=39992
        $this->server = new Process('exec php -S ' . self::$PARAMETERS['%base_host%'] . ' -t ' . self::$PARAMETERS['%working_dir%']);
        $this->server->start();
    }

    /**
     * @Given I have the following behat configuration:
     */
    public function iHaveTheFollowingBehatConfiguration(PyStringNode $string)
    {
        $string = $string->getRaw();
        $string = str_replace(array_keys(self::$PARAMETERS), array_values(self::$PARAMETERS), $string);
        self::$filesystem->dumpFile(self::$PARAMETERS['%working_dir%'] . '/behat.yml', $string);
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

        $this->behatProcess->setWorkingDirectory(self::$PARAMETERS['%working_dir%']);
        $this->behatProcess->setCommandLine(
            sprintf(
                '%s %s %s',
                $this->phpBin,
                escapeshellarg(BEHAT_BIN_PATH),
                $argumentsString
            )
        );

        // Don't reset the LANG variable on HHVM, because it breaks HHVM itself
        $env = $this->behatProcess->getEnv();
        $env['LANG'] = 'en'; // Ensures that the default language is en, whatever the OS locale is.
        $this->behatProcess->setEnv($env);

        $this->behatProcess->run();
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
        PHPUnit_Framework_Assert::assertContains(
            $this->getExpectedOutput($text),
            $this->getOutput()
        );
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

            PHPUnit_Framework_Assert::assertGreaterThan(0, $this->getExitCode());
        } else {
            if (0 !== $this->getExitCode()) {
                echo 'Actual output:' . PHP_EOL . PHP_EOL . $this->getOutput();
            }

            PHPUnit_Framework_Assert::assertSame(0, $this->getExitCode());
        }
    }

    private function getExpectedOutput(PyStringNode $expectedText)
    {
        $text = strtr($expectedText, array(
            '\'\'\'' => '"""',
            '%%TMP_DIR%%' => sys_get_temp_dir() . DIRECTORY_SEPARATOR,
            '%working_dir%%' => realpath(self::$PARAMETERS['%working_dir%'] . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR,
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
        return $this->behatProcess->getExitCode();
    }

    private function getOutput()
    {
        $output = $this->behatProcess->getErrorOutput() . $this->behatProcess->getOutput();

        // Normalize the line endings in the output
        if ("\n" !== PHP_EOL) {
            $output = str_replace(PHP_EOL, "\n", $output);
        }

        return trim(preg_replace("/ +$/m", '', $output));
    }
}
