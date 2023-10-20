<?php

namespace App\Library\Utils\Interfaces;

interface IntegrationInterface
{
    public function doRequest(string $serviceUrl, string $method = 'GET'): array;

    public function setConfigs(array $configs): void;
}