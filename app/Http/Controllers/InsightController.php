<?php

namespace App\Http\Controllers;

use App\Models\MemberOnboarding;
use Illuminate\Support\Facades\DB;

class InsightController extends Controller
{

    public function handleEmptyValues($data)
    {
        return !empty($data) ? $data : 0;
    }


    public function getOnbordingData($path_path)
    {
        $onboard_data = [];
        $headers = array();
        $row = 0;
        if (($handle = fopen($path_path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $num = count($data);
                $row++;
                if ($row == 1) {
                    $headers = $data;
                    continue;
                }
                $temp = array();
                for ($c = 0; $c < $num; $c++) {
                    $temp[$headers[$c]] = $this->handleEmptyValues($data[$c]);
                }

                array_push($onboard_data, $temp);
            }
            fclose($handle);
        }
        return $onboard_data;
    }

    public function getWeeklyRetentionData()
    {

        $weekly_retention =
            MemberOnboarding::query()->select([
                DB::raw('DATE_ADD(created_at, INTERVAL(2-DAYOFWEEK(created_at)) DAY) AS week_start'),
                DB::raw('SUM(CASE WHEN onboarding_perentage <= 100 THEN 1 ELSE 0 END) AS step_1'),
                DB::raw('SUM(CASE WHEN onboarding_perentage > 0 AND onboarding_perentage <= 100 THEN 1 ELSE 0 END) step_2'),
                DB::raw('SUM(CASE WHEN onboarding_perentage > 20 AND onboarding_perentage <= 100 THEN 1 ELSE 0 END) step_3'),
                DB::raw('SUM(CASE WHEN onboarding_perentage > 40 AND onboarding_perentage <= 100 THEN 1 ELSE 0 END) step_4'),
                DB::raw('SUM(CASE WHEN onboarding_perentage > 50 AND onboarding_perentage <= 100 THEN 1 ELSE 0 END) step_5'),
                DB::raw('SUM(CASE WHEN onboarding_perentage > 70 AND onboarding_perentage <= 100 THEN 1 ELSE 0 END) step_6'),
                DB::raw('SUM(CASE WHEN onboarding_perentage > 90 AND onboarding_perentage <= 100 THEN 1 ELSE 0 END) step_7'),
                DB::raw('SUM(CASE WHEN onboarding_perentage = 100 THEN 1 ELSE 0 END) step_8')
            ])
                ->groupBy('week_start')
                ->orderBy('week_start', 'ASC')
                ->get();

        return $weekly_retention;
    }


    public function weeklyRetentionDataAction()
    {
        $csv_path = storage_path('app/public/export.csv');
        $onboard_data = $this->getOnbordingData($csv_path);

        MemberOnboarding::query()->truncate();

        MemberOnboarding::insert($onboard_data);

        $weekly_retention = $this->getWeeklyRetentionData();

        $chartArray ["chart"] = array(
            "type" => "line"
        );
        $chartArray ["title"] = array(
            "text" => "Weekly Retention Curve"
        );
        $chartArray ["credits"] = array(
            "enabled" => false
        );
        $chartArray ["xAxis"] = array(
            "categories" => array()
        );
        $chartArray ["tooltip"] = array(
            "valueSuffix" => "%"
        );

        $categoryArray = array(
            0,
            20,
            40,
            50,
            70,
            90,
            99,
            100
        );

        $chartArray ["xAxis"] = array(
            "categories" => $categoryArray
        );
        $chartArray ["yAxis"] = array(
            "title" => array(
                "text" => "Total Onboarded"
            ),
            'labels' => array(
                'format' => '{value}%'
            ),
            'min' => '0',
            'max' => '100'
        );


        foreach ($weekly_retention as $week) {
            $dataArray = array();

            for ($i = 1; $i <= 8; $i++) {
                if ($i == 1) {
                    $dataArray[] = 100;
                } else {
                    $dataArray[] = round(($week->{"step_" . $i} / $week->step_1) * 100);
                }
            }


            $chartArray ["series"] [] = array(
                "name" => $week->week_start,
                "data" => $dataArray
            );
        }

        return response()->json($chartArray);
    }

}
