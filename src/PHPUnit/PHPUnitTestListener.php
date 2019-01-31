<?php
namespace KiwiTcmsPhpUnitPlugin\PHPUnit;

use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Warning;
use KiwiTcmsPhpUnitPlugin\Config\Config;

use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Factory\KiwiTCMSJsonRpcAuthenticatedClientFactory;

class PHPUnitTestListener implements TestListener
{
    protected $kiwiTcmsClient;

    public function __construct(string $configFile)
    {
        $config = new Config($configFile);
        $this->kiwiTcmsClient = KiwiTCMSJsonRpcAuthenticatedClientFactory::create($config);
        $this->kiwiTcmsClient->init();
    }

    public function __destruct()
    {
        $this->kiwiTcmsClient->finish();
    }

    public function addError(Test $test, \Throwable $t, float $time): void
    {
        $this->kiwiTcmsClient->addTestResult(
            $test->getName(),
            'ERROR',
            get_class($test),
            "Error: " . $t->getMessage() . "\n\n" . $t->getFile() . ':' . $t->getLine()
        );
    }

    public function addWarning(Test $test, Warning $e, float $time): void
    {
        $this->kiwiTcmsClient->addTestResult(
            $test->getName(),
            'WARNING',
            get_class($test),
            "Warning: " . $e->getMessage() . "\n\n" . $e->getFile() . ':' . $e->getLine()
        );
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        $this->kiwiTcmsClient->addTestResult(
            $test->getName(),
            'FAIL',
            get_class($test),
            "Failure: " . $e->getMessage() . "\n\n" . $e->getFile() . ':' . $e->getLine()
        );
    }

    public function addIncompleteTest(Test $test, \Throwable $t, float $time): void
    {
        $this->kiwiTcmsClient->addTestResult(
            $test->getName(),
            'INCOMPLETE',
            get_class($test),
            "Incomplete: " . $t->getMessage()
        );
    }

    public function addRiskyTest(Test $test, \Throwable $t, float $time): void
    {
        $this->kiwiTcmsClient->addTestResult(
            $test->getName(),
            'RISKY',
            get_class($test),
            "Risky: " . $t->getMessage()
        );
    }

    public function addSkippedTest(Test $test, \Throwable $t, float $time): void
    {
        $this->kiwiTcmsClient->addTestResult(
            $test->getName(),
            'SKIPPED',
            get_class($test),
            "Skipped: " . $t->getMessage()
        );
    }

    public function startTest(Test $test): void
    {
    }

    public function endTest(Test $test, float $time): void
    {
        $this->kiwiTcmsClient->addTestResult(
            $test->getName(),
            'PASS',
            get_class($test)
        );
    }

    public function startTestSuite(TestSuite $suite): void
    {
    }

    public function endTestSuite(TestSuite $suite): void
    {
    }
}
