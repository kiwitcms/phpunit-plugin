<?php

namespace KiwiTcmsPhpUnitPlugin\PHPUnit;

use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Warning;
use KiwiTcmsPhpUnitPlugin\Config\Config;
use KiwiTcmsPhpUnitPlugin\Config\ConfigException;
use KiwiTcmsPhpUnitPlugin\Api\ClientException;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Factory\KiwiTCMSJsonRpcAuthenticatedClientFactory;

class PHPUnitTestListener implements TestListener
{
    protected $kiwiTcmsClient;
    private $testStartTime;

    public function __construct()
    {
        set_exception_handler(function ($exception) {
            echo $exception->getMessage();
            echo "\n";
            echo $exception->getTraceAsString();
            echo "\n";
            throw $exception;
        });

        try {
            $config = new Config();
        } catch (ConfigException $e) {
            printf($e->getMessage());
            exit(1);
        }

        $this->kiwiTcmsClient = KiwiTCMSJsonRpcAuthenticatedClientFactory::create($config);

        try {
            $this->kiwiTcmsClient->init();
        } catch (ClientException $e) {
            printf($e->getMessage());
            exit(1);
        }
    }

    public function __destruct()
    {
        if ($this->kiwiTcmsClient) {
            $this->kiwiTcmsClient->finish();
        }
    }

    public function addError(Test $test, \Throwable $t, float $time): void
    {
        $this->kiwiTcmsClient->addTestResult(
            $test->getName(),
            'ERROR',
            get_class($test),
            "Error: " . $t->getMessage() . "\n\n" . $t->getFile() . ':' . $t->getLine(),
            $this->getExecutionTime()
        );
    }

    public function addWarning(Test $test, Warning $e, float $time): void
    {
        $this->kiwiTcmsClient->addTestResult(
            $test->getName(),
            'WARNING',
            get_class($test),
            "Warning: " . $e->getMessage() . "\n\n" . $e->getFile() . ':' . $e->getLine(),
            $this->getExecutionTime()
        );
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        $this->kiwiTcmsClient->addTestResult(
            $test->getName(),
            'FAIL',
            get_class($test),
            "Failure: " . $e->getMessage() . "\n\n" . $e->getFile() . ':' . $e->getLine(),
            $this->getExecutionTime()
        );
    }

    public function addIncompleteTest(Test $test, \Throwable $t, float $time): void
    {
        $this->kiwiTcmsClient->addTestResult(
            $test->getName(),
            'INCOMPLETE',
            get_class($test),
            "Incomplete: " . $t->getMessage(),
            $this->getExecutionTime()
        );
    }

    public function addRiskyTest(Test $test, \Throwable $t, float $time): void
    {
        $this->kiwiTcmsClient->addTestResult(
            $test->getName(),
            'RISKY',
            get_class($test),
            "Risky: " . $t->getMessage(),
            $this->getExecutionTime()
        );
    }

    public function addSkippedTest(Test $test, \Throwable $t, float $time): void
    {
        $this->kiwiTcmsClient->addTestResult(
            $test->getName(),
            'SKIPPED',
            get_class($test),
            "Skipped: " . $t->getMessage(),
            $this->getExecutionTime()
        );
    }

    public function startTest(Test $test): void
    {
        $this->testStartTime = microtime(true);
    }

    public function endTest(Test $test, float $time): void
    {
        $this->kiwiTcmsClient->addTestResult(
            $test->getName(),
            'PASS',
            get_class($test),
            "",
            $this->getExecutionTime()
        );
    }

    public function startTestSuite(TestSuite $suite): void
    {
    }

    public function endTestSuite(TestSuite $suite): void
    {
    }

    private function getExecutionTime()
    {
        return microtime(true) - $this->testStartTime;
    }
}
