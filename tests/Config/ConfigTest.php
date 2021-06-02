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

        $this->setOutputCallback(function () {
        });
    }

    public function tearDown()
    {
        parent::tearDown();
        foreach ($this->origEnv as $envKey => $envVal) {
            putenv($envKey . '=' . $envVal);
        }

        $config = Config::getDefaultConfFilename();
        if (file_exists($config)) {
            unlink($config);
        }
    }

    public function testMissingVars()
    {
        $this->expectException(ConfigException::class);

        $this->createConfigFile('tests/fixtures/configuration/empty.conf');

        new Config();
    }

    public function testAllSet()
    {
        $this->createConfigFile('tests/fixtures/configuration/all_set.conf');
        $config = new Config();

        $this->assertSame('localhost', $config->getUrl());
        $this->assertSame('username', $config->getUsername());
        $this->assertSame('password', $config->getPassword());
        $this->assertSame('product', $config->getProductName());
        $this->assertSame('version', $config->getProductVersion());
        $this->assertSame('build', $config->getBuild());
    }

    public function testMissingConfigApiUrl()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/.*url..*/');
        $this->createConfigFile('tests/fixtures/configuration/missing_api_url.conf');

        new Config('tests/fixtures/configuration/missing_api_url.conf');
    }

    public function testMissingConfigUsername()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/.*username.*/');
        $this->createConfigFile('tests/fixtures/configuration/missing_username.conf');

        new Config();
    }

    public function testMissingConfigPassword()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/.*password.*/');
        $this->createConfigFile('tests/fixtures/configuration/missing_password.conf');

        new Config();
    }

    public function testMissingConfigProduct()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/.*product.*/');
        $this->createConfigFile('tests/fixtures/configuration/missing_product.conf');

        new Config();
    }

    public function testMissingConfigProductVersion()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/.*product_version.*/');
        $this->createConfigFile('tests/fixtures/configuration/missing_product_version.conf');

        new Config();
    }

    public function testMissingConfigBuild()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/.*build.*/');
        $this->createConfigFile('tests/fixtures/configuration/missing_build.conf');

        new Config();
    }

    public function testMissingEnvVars()
    {
        $this->expectException(ConfigException::class);

        new Config();
    }

    public function testEnvVarsSet()
    {
        putenv('TCMS_API_URL=url');
        putenv('TCMS_USERNAME=username');
        putenv('TCMS_PASSWORD=password');
        putenv('TCMS_PRODUCT=product');
        putenv('TCMS_PRODUCT_VERSION=version');
        putenv('TCMS_BUILD=build');

        $config = new Config();

        $this->assertSame('url', $config->getUrl());
        $this->assertSame('username', $config->getUsername());
        $this->assertSame('password', $config->getPassword());
        $this->assertSame('product', $config->getProductName());
        $this->assertSame('version', $config->getProductVersion());
        $this->assertSame('build', $config->getBuild());
    }

    public function testEnvApiUrlSet()
    {
        putenv('TCMS_API_URL=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?url).*$/');

        new Config();
    }

    public function testEnvUsernameSet()
    {
        putenv('TCMS_USERNAME=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?username).*$/');

        new Config();
    }

    public function testEnvPasswordSet()
    {
        putenv('TCMS_PASSWORD=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?password).*$/');

        new Config();
    }

    public function testEnvProductSet()
    {
        putenv('TCMS_PRODUCT=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?product,).*$/');

        new Config();
    }

    public function testEnvProductAlternativeTravisRepoSlugSet()
    {
        putenv('TRAVIS_REPO_SLUG=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?product,).*$/');

        new Config();
    }

    public function testEnvProductAlternativeJobNameSet()
    {
        putenv('JOB_NAME=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?product,).*$/');

        new Config();
    }

    public function testEnvProductVersionSet()
    {
        putenv('TCMS_PRODUCT_VERSION=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?product_version).*$/');

        new Config();
    }

    public function testEnvProductVersionAlternativeTravisCommitSet()
    {
        putenv('TRAVIS_COMMIT=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?product_version).*$/');

        new Config();
    }

    public function testEnvProductVersionAlternativeTravisPullRequestShaSet()
    {
        putenv('TRAVIS_PULL_REQUEST_SHA=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?product_version).*$/');

        new Config();
    }

    public function testEnvProductVersionAlternativeGitCommitSet()
    {
        putenv('GIT_COMMIT=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?product_version).*$/');

        new Config();
    }

    public function testEnvBuildSet()
    {
        putenv('TCMS_BUILD=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?build).*$/');

        new Config();
    }

    public function testEnvBuildAlternativeTravisBuildNumberSet()
    {
        putenv('TRAVIS_BUILD_NUMBER=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?build).*$/');

        new Config();
    }

    public function testEnvBuildAlternativeBuildNumberSet()
    {
        putenv('BUILD_NUMBER=something');
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessageRegExp('/^(?!.*?build).*$/');

        new Config();
    }

    private function createConfigFile(string $source)
    {
        $target = Config::getDefaultConfFilename();
        if (file_exists($target)) {
            unlink($target);
        }

        copy($source, $target);
    }
}
