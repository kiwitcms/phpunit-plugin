<?php

namespace KiwiTcmsPhpUnitPlugin\Config;

use KiwiTcmsPhpUnitPlugin\Config\ConfigInterface;
use KiwiTcmsPhpUnitPlugin\Config\ConfigException;
use Dotenv\Dotenv;

class Config implements ConfigInterface
{

    public function __construct($configFile)
    {
        $configFilePath = getcwd() . DIRECTORY_SEPARATOR . $configFile;
        $configPathInfo = pathinfo($configFilePath);

        try {
            $dotenv = Dotenv::create($configPathInfo['dirname'], $configPathInfo['basename']);
            $dotenv->load();
        } catch (\Exception $e) {
            throw new ConfigException($e->getMessage());
        }

        $this->stopIfEmptyEnvVars();
    }

    private function stopIfEmptyEnvVars()
    {
        $requiredEnvVars = [
            'TCMS_URL',
            'TCMS_USERNAME',
            'TCMS_PASSWORD',
            'TCMS_PRODUCT',
            'TCMS_PRODUCT_VERSION',
            'TCMS_BUILD',
        ];

        $envVarsNotSet = [];
        foreach ($requiredEnvVars as $value) {
            if (empty(getenv($value))) {
                $envVarsNotSet[] = $value;
            }
        }

        if (!empty($envVarsNotSet)) {
            throw new ConfigException(implode(', ', $envVarsNotSet) . " are not set.");
        }
    }

    public function getUrl()
    {
        return getenv('TCMS_URL');
    }

    public function getUsername()
    {
        return getenv('TCMS_USERNAME');
    }

    public function getPassword()
    {
        return getenv('TCMS_PASSWORD');
    }

    public function getVerifySslCertificates()
    {
        return getenv('TCMS_VERIFY_SSL_CERTIFICATES') ?: false;
    }

    public function getProductName()
    {
        return getenv('TRAVIS_REPO_SLUG') ? : getenv('TCMS_PRODUCT');
    }

    public function getProductVersion()
    {
        return getenv('TRAVIS_COMMIT') ? : getenv('TCMS_PRODUCT_VERSION');
    }

    public function getBuild()
    {
        return getenv('TRAVIS_BUILD_NUMBER') ? : getenv('TCMS_BUILD');
    }

    public function getTestRunId(): int
    {
        return (int) getenv('TCMS_RUN_ID');
    }
}
