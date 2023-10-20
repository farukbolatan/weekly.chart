<?php

namespace App\Library\Utils\Abstracts;

abstract class Model
{
    public $entity;
    public $doctrine;

    public function getAll($doctrine): array
    {
        $query = $doctrine->getRepository($this->entity)
            ->createQueryBuilder('employees')
            ->getQuery();
        return $query->getArrayResult();
    }
}