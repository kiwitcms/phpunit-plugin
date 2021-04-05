<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Factory;

use Graze\GuzzleHttp\JsonRpc\Client as GuzzleJsonRpcClient;
use KiwiTcmsPhpUnitPlugin\Config\ConfigInterface;

class GuzzleJsonRpcClientFactory
{
    public static function create(ConfigInterface $config, array $headers = []): GuzzleJsonRpcClient
    {
        $jsonRpcUrl = rtrim($config->getUrl(), "/") . '/json-rpc/';

        $guzzleJsonRpcClient = GuzzleJsonRpcClient::factory($jsonRpcUrl, [
            'verify' => true,
            'rpc_error' => true,
            'headers' => $headers,
        ]);

        return $guzzleJsonRpcClient;
    }
}
