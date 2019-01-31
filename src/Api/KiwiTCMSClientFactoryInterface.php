<?php

namespace KiwiTcmsPhpUnitPlugin\Api;

use KiwiTcmsPhpUnitPlugin\Api\ClientInterface;
use KiwiTcmsPhpUnitPlugin\Config\ConfigInterface;

interface KiwiTCMSClientFactoryInterface
{
    public static function create(ConfigInterface $config) : ClientInterface;
}
