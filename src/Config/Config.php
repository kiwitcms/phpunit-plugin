<?php

namespace KiwiTcmsPhpUnitPlugin\Config;

use KiwiTcmsPhpUnitPlugin\Config\ConfigInterface;
use KiwiTcmsPhpUnitPlugin\Config\ConfigException;
use M1\Env\Parser;

class Config implements ConfigInterface
{
    /**
     * @var string
     */
    private $pluginName;

    /**
     * @var array
     */
    private $config;

    public function __construct(?string $configFilePath = null)
    {
        $this->pluginName = 'KiwiTCMS Plugin';

        $this->config = $this->getDefaultConfig();

        if ($configFilePath && is_file($configFilePath)) {
            $configText = $this->loadConfigFromFile($configFilePath);
        } else {
            $configText = $this->loadConfigFromEnvironmentVariables();
        }

        $this->config = array_merge($this->config, Parser::parse($configText));

        $this->stopIfEmptyConfigParameter();
    }

    private function getDefaultConfig(): array
    {
        return [
            'TCMS_API_URL' => null,
            'TCMS_USERNAME' => null,
            'TCMS_PASSWORD' => null,
            'TCMS_PRODUCT' => null,
            'TCMS_PRODUCT_VERSION' => null,
            'TCMS_BUILD' => null,
            'TCMS_VERIFY_SSL_CERTIFICATES' => true,
            'TCMS_RUN_ID' => null,
        ];
    }

    private function getAlternativeEnvironmentVariableNames(): array
    {
        return [
            'TCMS_PRODUCT' => [
                'TRAVIS_REPO_SLUG', 'JOB_NAME'
            ],
            'TCMS_PRODUCT_VERSION' => [
                'TRAVIS_COMMIT', 'TRAVIS_PULL_REQUEST_SHA', 'GIT_COMMIT'
            ],
            'TCMS_BUILD' => [
                'TRAVIS_BUILD_NUMBER', 'BUILD_NUMBER'
            ],
        ];
    }

    private function loadConfigFromFile($configFilePath)
    {
        printf("%s: Loading configuration from %s...\n", $this->pluginName, realpath($configFilePath));

        return file_get_contents($configFilePath);
    }

    private function getEnvVarValue(string $envVarName)
    {
        $envVarValue = getenv($envVarName);

        if ($envVarValue !== FALSE && $envVarValue !== "") {
            return $envVarValue;
        }

        return $this->getEnvVarAlternativeNameValue($envVarName);
    }

    private function getEnvVarAlternativeNameValue(string $envVarName)
    {
        $alternativeEnvVarsNames = $this->getAlternativeEnvironmentVariableNames();
        $envVarsWithAlternatives = array_keys($alternativeEnvVarsNames);

        if (!in_array($envVarName, $envVarsWithAlternatives)){
            return FALSE;
        }

        foreach ($alternativeEnvVarsNames[$envVarName] as $alternativeEnvVarName) {
            $envVarValue = getenv($alternativeEnvVarName);

            if ($envVarValue !== FALSE && $envVarValue !== "") {
                return $envVarValue;
            }
        }

        return FALSE;
    }

    private function loadConfigFromEnvironmentVariables()
    {
        $configText = '';
        $envVars = array_keys($this->getDefaultConfig());

        foreach ($envVars as $envVarName) {
            $envVarValue = $this->getEnvVarValue($envVarName);
            if ($envVarValue === false) {
                continue;
            }

            $configText .= sprintf("%s=%s\n", $envVarName, $envVarValue);
        }

        return $configText;
    }

    private function stopIfEmptyConfigParameter()
    {
        $requiredParameters = [
            'TCMS_API_URL',
            'TCMS_USERNAME',
            'TCMS_PASSWORD',
            'TCMS_PRODUCT',
            'TCMS_PRODUCT_VERSION',
            'TCMS_BUILD',
        ];

        $parametersNotSet = [];
        foreach ($requiredParameters as $parameter) {
            if (empty($this->config[$parameter])) {
                $parametersNotSet[] = $parameter;
            }
        }

        if (!empty($parametersNotSet)) {
            $msg = count($parametersNotSet) === 1 ? " is not set." : " are not set.";
            $fullMsg = sprintf("%s: %s %s\n", $this->pluginName, implode(', ', $parametersNotSet), $msg);
            throw new ConfigException($fullMsg);
        }
    }

    public function getUrl(): string
    {
        return $this->config['TCMS_API_URL'];
    }

    public function getUsername(): string
    {
        return $this->config['TCMS_USERNAME'];
    }

    public function getPassword(): string
    {
        return $this->config['TCMS_PASSWORD'];
    }

    public function getVerifySslCertificates(): bool
    {
        return $this->config['TCMS_VERIFY_SSL_CERTIFICATES'];
    }

    public function getProductName(): string
    {
        return $this->config['TCMS_PRODUCT'];
    }

    public function getProductVersion(): string
    {
        return $this->config['TCMS_PRODUCT_VERSION'];
    }

    public function getBuild(): string
    {
        return $this->config['TCMS_BUILD'];
    }

    public function getTestRunId(): ?int
    {
        return $this->config['TCMS_RUN_ID'];
    }
}
