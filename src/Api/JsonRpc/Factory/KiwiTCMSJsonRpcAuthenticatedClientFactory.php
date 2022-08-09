<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Factory;

use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\ClassificationRepository;
use KiwiTcmsPhpUnitPlugin\Api\KiwiTCMSClientFactoryInterface;
use KiwiTcmsPhpUnitPlugin\Api\ClientInterface;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Client;
use KiwiTcmsPhpUnitPlugin\Config\Config;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Factory\GuzzleJsonRpcClientFactory;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\ProductRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\ProductVersionRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\TestPlanRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\TestRunRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\BuildRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\TestCaseRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\CategoryRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\TestExecutionRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\UserRepository;

class KiwiTCMSJsonRpcAuthenticatedClientFactory implements KiwiTCMSClientFactoryInterface
{
    public static function create(Config $config): ClientInterface
    {
        $guzzleJsonRpcClient = GuzzleJsonRpcClientFactory::create($config);

        $authResponse = $guzzleJsonRpcClient->send($guzzleJsonRpcClient->request(123, 'Auth.login', [
            $config->getUsername(),
            $config->getPassword()
        ]));

        $sessionId = $authResponse->getRpcResult();

        $guzzleJsonRpcAuthenticatedClient = GuzzleJsonRpcClientFactory::create($config, [
            'Cookie' => 'sessionid=' . $sessionId,
        ]);

        $client = new Client(
            $guzzleJsonRpcAuthenticatedClient,
            $config,
            new ProductRepository($guzzleJsonRpcAuthenticatedClient),
            new ProductVersionRepository($guzzleJsonRpcAuthenticatedClient),
            new TestPlanRepository($guzzleJsonRpcAuthenticatedClient),
            new TestRunRepository($guzzleJsonRpcAuthenticatedClient),
            new BuildRepository($guzzleJsonRpcAuthenticatedClient),
            new TestCaseRepository($guzzleJsonRpcAuthenticatedClient),
            new CategoryRepository($guzzleJsonRpcAuthenticatedClient),
            new TestExecutionRepository($guzzleJsonRpcAuthenticatedClient),
            new UserRepository($guzzleJsonRpcAuthenticatedClient),
            new ClassificationRepository($guzzleJsonRpcAuthenticatedClient)
        );

        return $client;
    }
}
