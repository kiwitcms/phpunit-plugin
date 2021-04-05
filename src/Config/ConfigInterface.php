<?php

namespace KiwiTcmsPhpUnitPlugin\Config;

interface ConfigInterface
{
    public function getUrl();
    public function getUsername();
    public function getPassword();
    public function getProductName();
    public function getProductVersion();
    public function getBuild();
    public function getTestRunId(): ?int;
}
