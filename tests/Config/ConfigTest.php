<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use KiwiTcmsPhpUnitPlugin\Config\Config;
use KiwiTcmsPhpUnitPlugin\Config\ConfigException;

class ConfigTest extends TestCase
{

    private $origEnv;

    public function setUp()
    {
        parent::setUp();
        $this->origEnv = getenv();
        foreach ($this->origEnv as $envKey => $envVal) {
            putenv($envKey . '=');
        }
    }

    public function tearDown()
    {
        parent::tearDown();
        foreach ($this->origEnv as $envKey => $envVal) {
            putenv($envKey . '=' . $envVal);
        }
    }

    public function testMissingEnvVars()
    {
        $this->expectException(ConfigException::class);

        new Config('tests/fixtures/empty.conf');
    }

    public function testMissingUrl()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/.*TCMS_URL.*/');

        new Config('tests/fixtures/empty.conf');
    }

    public function testUrlSet()
    {
        putenv('TCMS_URL=something');

        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?TCMS_URL).*$/');

        new Config('tests/fixtures/empty.conf');
    }

    public function testMissingUsername()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/.*TCMS_USERNAME.*/');

        new Config('tests/fixtures/empty.conf');
    }

    public function testUsernameSet()
    {
        putenv('TCMS_USERNAME=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?TCMS_USERNAME).*$/');

        new Config('tests/fixtures/empty.conf');
    }

    public function testMissingPassword()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/.*TCMS_PASSWORD.*/');

        new Config('tests/fixtures/empty.conf');
    }

    public function testPasswordSet()
    {
        putenv('TCMS_PASSWORD=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?TCMS_PASSWORD).*$/');

        new Config('tests/fixtures/empty.conf');
    }

    public function testMissingProduct()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/.*TCMS_PRODUCT.*/');

        new Config('tests/fixtures/empty.conf');
    }

    public function testTcmsProductSet()
    {
        putenv('TCMS_PRODUCT=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?TCMS_PRODUCT,).*$/');

        new Config('tests/fixtures/empty.conf');
    }

    public function testMissingProductVersion()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/.*TCMS_PRODUCT_VERSION.*/');

        new Config('tests/fixtures/empty.conf');
    }

    public function testTcmsProductVersionSet()
    {
        putenv('TCMS_PRODUCT_VERSION=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?TCMS_PRODUCT_VERSION).*$/');

        new Config('tests/fixtures/empty.conf');
    }

    public function testMissingBuild()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/.*TCMS_BUILD.*/');

        new Config('tests/fixtures/empty.conf');
    }

    public function testTcmsBuildSet()
    {
        putenv('TCMS_BUILD=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?TCMS_BUILD).*$/');

        new Config('tests/fixtures/empty.conf');
    }

    public function testTcmsEnvVarsSet()
    {
        putenv('TCMS_URL=url');
        putenv('TCMS_USERNAME=username');
        putenv('TCMS_PASSWORD=password');
        putenv('TCMS_PRODUCT=product');
        putenv('TCMS_PRODUCT_VERSION=version');
        putenv('TCMS_BUILD=build');

        $config = new Config('tests/fixtures/empty.conf');

        $this->assertSame('url', $config->getUrl());
        $this->assertSame('username', $config->getUsername());
        $this->assertSame('password', $config->getPassword());
        $this->assertSame('product', $config->getProductName());
        $this->assertSame('version', $config->getProductVersion());
        $this->assertSame('build', $config->getBuild());
    }
}
