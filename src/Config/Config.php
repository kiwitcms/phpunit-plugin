<?php

namespace KiwiTcmsPhpUnitPlugin\Config;

use KiwiTcmsPhpUnitPlugin\Config\ConfigInterface;
use KiwiTcmsPhpUnitPlugin\Config\ConfigException;

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

        $this->config = $this->applyConfigFileValuesToConfig($this->config, $configFilePath);

        $this->config = $this->applyEnvironmentValuesToConfig($this->config);

        if ($this->config['verify_ssl_certificates'] === null) {
            $this->config['verify_ssl_certificates'] = true;
        }

        $this->stopIfEmptyConfigParameter();
    }

    private function getDefaultConfig(): array
    {
        return [
            'url' => null,
            'username' => null,
            'password' => null,
            'product' => null,
            'product_version' => null,
            'build' => null,
            'verify_ssl_certificates' => null,
            'run_id' => null,
        ];
    }

    private function applyConfigFileValuesToConfig(array $config, ?string $configFilePath = null): array {
        printf("%s: Loading configuration from %s...\n", $this->pluginName, realpath($configFilePath));

        if (!$configFilePath || !is_file($configFilePath)) {
            return $config;
        }

        $configFileValues = parse_ini_file($configFilePath, true, INI_SCANNER_TYPED);

        foreach ($config as $key => $value) {
            if (!isset($configFileValues['tcms'][$key])) {
                continue;
            }

            $config[$key] = $configFileValues['tcms'][$key];
        }

        return $config;
    }

    private function applyEnvironmentValuesToConfig(array $config): array {
        printf("%s: Loading configuration from environment...\n", $this->pluginName);

        $configOptionsEnvVarsNames = $this->getEnvironmentVariableNames();

        foreach ($configOptionsEnvVarsNames as $configOption => $envVarNames) {
            if ($config[$configOption] !== null && $config[$configOption] !== "") {
                continue;
            }

            foreach ($envVarNames as $envVar) {
                $envVarValue = getenv($envVar);

                if ($envVarValue !== false && $envVarValue !== "") {
                    $config[$configOption] = $envVarValue;
                }
            }
        }

        return $config;
    }

    private function getEnvironmentVariableNames(): array
    {
        return [
            'url' => ['TCMS_API_URL'],
            'username' => ['TCMS_USERNAME'],
            'password' => ['TCMS_PASSWORD'],
            'product' => [
                'TCMS_PRODUCT', 'TRAVIS_REPO_SLUG', 'JOB_NAME'
            ],
            'product_version' => [
                'TCMS_PRODUCT_VERSION', 'TRAVIS_COMMIT', 'TRAVIS_PULL_REQUEST_SHA', 'GIT_COMMIT'
            ],
            'build' => [
                'TCMS_BUILD', 'TRAVIS_BUILD_NUMBER', 'BUILD_NUMBER'
            ],
            'verify_ssl_certificates' => ['TCMS_VERIFY_SSL_CERTIFICATES'],
            'run_id' => ['TCMS_RUN_ID'],
        ];
    }

    private function stopIfEmptyConfigParameter()
    {
        $requiredParameters = [
            'url',
            'username',
            'password',
            'product',
            'product_version',
            'build',
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
        return $this->config['url'];
    }

    public function getUsername(): string
    {
        return $this->config['username'];
    }

    public function getPassword(): string
    {
        return $this->config['password'];
    }

    public function getVerifySslCertificates(): bool
    {
        return $this->config['verify_ssl_certificates'];
    }

    public function getProductName(): string
    {
        return $this->config['product'];
    }

    public function getProductVersion(): string
    {
        return $this->config['product_version'];
    }

    public function getBuild(): string
    {
        return $this->config['build'];
    }

    public function getTestRunId(): ?int
    {
        return $this->config['run_id'];
    }
}
