<?php

namespace App\Model;

use App\Entity\EntityBusinessCharts;
use App\Library\Utils\Abstracts\Model;

class BusinessCharts extends Model
{
    public $entity = EntityBusinessCharts::class;

    public function bulkImport($doctrine, $data)
    {
        $metadata = $doctrine->getClassMetadata($this->entity);
        $tableName = $metadata->getTableName();
        $name = $metadata->getColumnName('name');
        $cookTime = $metadata->getColumnName('cook_time');

        $values = [];
        foreach ($data as $datum) {
            $values[] = '("' . ($datum[$name] . '", ' . $datum[$cookTime] . ')');
        }

        $values = implode(', ', $values);

        $query = "INSERT INTO `{$tableName}` (`{$name}`, `{$cookTime}`) VALUES {$values}";

        return $doctrine->getConnection()->executeUpdate($query);
    }

    public function calculateWorkAssigment($employees, $businessCharts): array
    {
        $formattedEmployees = [];
        $employeesCount = 0;
        $usedSpeeds = [];
        foreach ($employees as $employee) {
            $employeesCount++;
            if (!in_array($employee['work_speed'], $usedSpeeds)) {
                $usedSpeeds[] = $employee['work_speed'];
            }

            $formattedEmployees[$employee['work_speed']][] = $employee['name'];
        }

        $formattedBusinessCharts = [];
        $totalWork = 0;
        foreach ($businessCharts as $businessChart) {
            $totalWork++;
            if ($businessChart['cook_time'] != 0) {
                $formattedBusinessCharts[$businessChart['cook_time']][] = $businessChart['name'];
            }
        }

        ksort($formattedEmployees);
        ksort($formattedBusinessCharts);

        $employeesWithHours = [];

        #if there is work at their own pace, assign it first.
        foreach ($formattedEmployees as $speed => $employees) {
            if (!empty($formattedBusinessCharts[$speed])) {
                foreach ($formattedBusinessCharts[$speed] as $key => $businessChartName) {
                    foreach ($employees as $employee) {
                        $employeesWithHours[$employee]['minutes'] = ($employeesWithHours[$employee]['minutes'] ?? 0) + 60;
                        $employeesWithHours[$employee]['each_minutes'][] = 60;
                        unset($formattedBusinessCharts[$speed][$key]);
                        $totalWork--;
                    }
                }
            }
            unset($formattedBusinessCharts[$speed]);
        }

        $perJobCount = $totalWork / $employeesCount;

        $durationBasedSpeed = [];
        foreach ($formattedEmployees as $speed => $employees) {
            $durationBasedSpeed[$speed] = (int)(($speed * $perJobCount) / count($employees));
        }

        #assign the remaining work based on the speed of the workers and the number of workers at the same speed
        $nextEmployee = false;
        foreach ($formattedEmployees as $speed => $employees) {
            $perJobCount = $durationBasedSpeed[$speed];
            foreach ($employees as $employee) {
                nextEmployee:
                if ($nextEmployee) {
                    $nextEmployee = false;
                    continue;
                }
                $receivedJobs = 0;
                foreach ($formattedBusinessCharts as $cookTime => $formattedBusinessChart) {
                    foreach ($formattedBusinessChart as $work) {
                        if ($perJobCount == $receivedJobs) {
                            $nextEmployee = true;
                            goto nextEmployee;
                        }
                        $minute = (int)(($cookTime / $speed) * 60);
                        $employeesWithHours[$employee]['minutes'] = ($employeesWithHours[$employee]['minutes'] ?? 0) + $minute;
                        $employeesWithHours[$employee]['each_minutes'][] = $minute;
                        $receivedJobs++;
                    }
                }
            }
        }

        $this->addWeeks($employeesWithHours);

        return $employeesWithHours;
    }

    public function addWeeks(&$employeesWithHours)
    {
        foreach ($employeesWithHours as &$employeesWithHour) {
            $hour = $employeesWithHour['minutes'] / 60;
            $week = $hour / 45;
            if (is_float($week)) {
                $week = (int)$week + 1;
            }
            $employeesWithHour['week'] = $week;
        }
    }

}