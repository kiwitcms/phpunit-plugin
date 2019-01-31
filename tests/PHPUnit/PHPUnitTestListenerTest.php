<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use KiwiTcmsPhpUnitPlugin\PHPUnit\PHPUnitTestListener;
use PHPUnit\Framework\Warning;
use ReflectionClass;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Client;
use PHPUnit\Framework\AssertionFailedError;

/**
 * @phpcs:disable Squiz.WhiteSpace.ScopeClosingBrace.ContentBefore
 */

class PHPUnitTestListenerTest extends TestCase
{
    public function testAddError()
    {
        $testObject = new class extends TestCase {};
        $phpUnitTestListenerMock = $this->createListenerMockWithStatus($testObject, 'ERROR');
        $phpUnitTestListenerMock->addError($testObject, new \Exception(), time());
    }

    public function testAddWarning()
    {
        $testObject = new class extends TestCase {};
        $phpUnitTestListenerMock = $this->createListenerMockWithStatus($testObject, 'WARNING');
        $phpUnitTestListenerMock->addWarning($testObject, new Warning(), time());
    }

    public function testAddFailure()
    {
        $testObject = new class extends TestCase {};
        $phpUnitTestListenerMock = $this->createListenerMockWithStatus($testObject, 'FAIL');
        $phpUnitTestListenerMock->addFailure($testObject, new AssertionFailedError(), time());
    }

    public function testAddIncompleteTest()
    {
        $testObject = new class extends TestCase {};
        $phpUnitTestListenerMock = $this->createListenerMockWithStatus($testObject, 'INCOMPLETE');
        $phpUnitTestListenerMock->addIncompleteTest($testObject, new \Exception(), time());
    }

    public function testAddRiskyTest()
    {
        $testObject = new class extends TestCase {};
        $phpUnitTestListenerMock = $this->createListenerMockWithStatus($testObject, 'RISKY');
        $phpUnitTestListenerMock->addRiskyTest($testObject, new \Exception(), time());
    }

    public function testAddSkippedTest()
    {
        $testObject = new class extends TestCase {};
        $phpUnitTestListenerMock = $this->createListenerMockWithStatus($testObject, 'SKIPPED');
        $phpUnitTestListenerMock->addSkippedTest($testObject, new \Exception(), time());
    }

    public function testEndTest()
    {
        $testObject = new class extends TestCase {};

        $clientStub = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientStub->expects($this->once())
            ->method('addTestResult')
            ->with(
                $this->equalTo($testObject->getName()),
                $this->equalTo('PASS'),
                $this->equalTo(get_class($testObject))
            );

        $phpUnitTestListenerMock = $this->getMockBuilder(PHPUnitTestListener::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $this->setProtectedProperty($phpUnitTestListenerMock, 'kiwiTcmsClient', $clientStub);

        $phpUnitTestListenerMock->endTest($testObject, time());
    }

    private function setProtectedProperty($object, $property, $value)
    {
        $reflection = new ReflectionClass($object);
        $reflection_property = $reflection->getProperty($property);
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($object, $value);
    }

    private function createListenerMockWithStatus(TestCase $object, string $status)
    {
        $clientStub = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientStub->expects($this->once())
            ->method('addTestResult')
            ->with(
                $this->equalTo($object->getName()),
                $this->equalTo($status),
                $this->equalTo(get_class($object)),
                $this->anything()
            );

        $phpUnitTestListenerMock = $this->getMockBuilder(PHPUnitTestListener::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $this->setProtectedProperty($phpUnitTestListenerMock, 'kiwiTcmsClient', $clientStub);

        return $phpUnitTestListenerMock;
    }
}
