<?php

namespace App\Model;

use App\Entity\EntityEmployees;
use App\Library\Utils\Abstracts\Model;

class Employees extends Model
{
    public $entity = EntityEmployees::class;
}