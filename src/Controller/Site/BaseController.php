<?php

namespace App\Controller\Site;

use App\Model\Employees;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    public function list(): Response
    {
        $values = (new Employees())->getAll($this->getDoctrine());

        return $this->render('/list.twig', [
            'values' => $values
        ]);
    }
}