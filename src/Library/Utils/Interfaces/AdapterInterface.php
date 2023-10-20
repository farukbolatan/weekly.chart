<?php

namespace App\Library\Utils\Interfaces;

interface AdapterInterface extends IntegrationInterface
{
    public function getServiceUrl(): string;

    public function getAdapterName(): string;
}