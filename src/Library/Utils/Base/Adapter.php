<?php

namespace App\Library\Utils\Base;

use App\Helper\AdapterHelper;
use App\Library\Exceptions\NotFoundException;
use App\Library\Utils\Interfaces\AdapterInterface;

class Adapter
{
    /**
     * @param string $name
     * @return AdapterInterface
     * @throws NotFoundException
     */
    public function getByName(string $name): AdapterInterface
    {
        $configs = AdapterHelper::get($name);
        return $this->get($configs);
    }

    /**
     * @param array $configs
     * @return AdapterInterface
     * @throws NotFoundException
     */
    public function get(array $configs): AdapterInterface
    {
        $className = AdapterHelper::ROOT . ($configs['path'] ?? 'NotFound');
        if (!class_exists($className)) {
            throw new NotFoundException('Adapter not found.');
        }

        /** @var AdapterInterface $className */
        return new $className($configs);
    }
}