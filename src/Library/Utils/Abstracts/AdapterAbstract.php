<?php


namespace App\Library\Utils\Abstracts;

use App\Library\Exceptions\CurlException;
use App\Library\Exceptions\NotFoundException;
use App\Library\Utils\Interfaces\AdapterInterface;
use Exception;

abstract class AdapterAbstract implements AdapterInterface
{
    private array $configs = [];

    public function __construct($configs)
    {
        $this->setConfigs($configs);
    }

    /**
     * @param string $serviceUrl
     * @param string $method
     * @return array
     * @throws NotFoundException
     */
    public function doRequest(string $serviceUrl, string $method = 'GET'): array
    {
        if (empty($serviceUrl)) {
            throw new NotFoundException('Undefined endpoint URL.');
        }

        $curl = curl_init();

        $request = [
            CURLOPT_URL => $serviceUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method
        ];

        curl_setopt_array($curl, $request);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $errorCode = curl_errno($curl);

        curl_close($curl);

        $success = true;
        $message = 'OK';
        if (!empty($error) || empty($response)) {
            $success = false;
            $message = "Curl error, error code:{$errorCode} #{$error}";
        }

        $response = $this->formatResponse($response);

        return [$success, $message, $response];
    }

    public function formatResponse($response)
    {
        if (is_string($response)) {
            try {
                $response = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
            } catch (Exception $jsonException) {
                $response = [];
            }
            return $response;
        }

        if (is_array($response)) {
            return $response;
        }

        return [];
    }

    public function setConfigs(array $configs): void
    {
        $this->configs = $configs;
    }

    public function getServiceUrl(): string
    {
        return $this->configs['service_url'] ?? '';
    }

    public function getAdapterName(): string
    {
        return $this->configs['name'] ?? '';
    }
}