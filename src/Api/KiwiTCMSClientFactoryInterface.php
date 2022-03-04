<?php

namespace KiwiTcmsPhpUnitPlugin\Api;

use KiwiTcmsPhpUnitPlugin\Api\ClientInterface;
use KiwiTcmsPhpUnitPlugin\Config\Config;

interface KiwiTCMSClientFactoryInterface
{
    public static function create(Config $config) : ClientInterface;
}
