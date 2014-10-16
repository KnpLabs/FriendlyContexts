<?php

namespace Context;

use Behat\Behat\Context\Context;

class ServerContext implements Context
{
    const SERVER_ADDRESS = "127.0.0.1:8080";

    private static $proc;

    /**
     * @BeforeSuite
     */
    public static function start()
    {
        self::$proc = sprintf('/tmp/%s', md5(time() . uniqid()));
        $command = sprintf(
            'php -S %s -t %s/../',
            self::SERVER_ADDRESS,
            __DIR__
        );
        $outputfile = '/dev/null';
        shell_exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $command, $outputfile, self::$proc));

        $loop = 0;
        while ($loop < 5) {
            try {
                self::test();
                return;
            } catch (\Exception $ex) {
                $loop++;
                sleep(1);
            }
        }
    }

    /**
     * @BeforeScenario
     */
    public static function test()
    {
        $ch = curl_init(sprintf('%s/html/index.html', self::SERVER_ADDRESS));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);

        if (200 !== curl_getinfo($ch, CURLINFO_HTTP_CODE)) {

            throw new \Exception('PHP builtin server is down.');
        } else {

            return;
        }
    }

    /**
     * @AfterSuite
     */
    public static function stop()
    {
        if (file_exists(self::$proc)) {
            $pids = file(self::$proc);
            foreach ($pids as $pid) {
                shell_exec('kill -9 ' . $pid);
            }
            unlink(self::$proc);
        }
    }
}
