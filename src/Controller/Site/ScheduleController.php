<?php

namespace App\Controller\Site;

use App\Model\BusinessCharts;
use App\Model\Employees;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ScheduleController extends Controller
{
    public function list()
    {
        $doctrine = $this->getDoctrine();

        $mBusinessCharts = new BusinessCharts();
        $businessCharts = $mBusinessCharts->getAll($doctrine);

        $mEmployees = new Employees();
        $employees = $mEmployees->getAll($doctrine);

        $response = $mBusinessCharts->calculateWorkAssigment($employees, $businessCharts);

        return $this->render('/calculate.twig', [
            'values' => $response
        ]);
    }
}