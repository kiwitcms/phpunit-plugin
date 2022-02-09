<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Factory;

use Graze\GuzzleHttp\JsonRpc\Client as GuzzleJsonRpcClient;
use KiwiTcmsPhpUnitPlugin\Config\ConfigInterface;

class GuzzleJsonRpcClientFactory
{
    public static function create(ConfigInterface $config, array $headers = []): GuzzleJsonRpcClient
    {
        $jsonRpcUrl = str_replace('xml-rpc', 'json-rpc', $config->getUrl());

        $guzzleJsonRpcClient = GuzzleJsonRpcClient::factory($jsonRpcUrl, [
            'verify' => true,
            'rpc_error' => true,
            'headers' => $headers,
        ]);

        return $guzzleJsonRpcClient;
    }
}
