<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;

class FootballStaffController extends Controller
{
    public function available(): Response
    {
        $path = MOCK_DIR . "football/available-staff.json";
        $staff = \App\Helpers\Common::getJsonFileData($path);
        return $this->json($staff);
    }

    public function own(): Response
    {
        $path = MOCK_DIR . "football/available-staff.json";
        $staff = \App\Helpers\Common::getJsonFileData($path);
        return $this->json($staff);
    }

    public function scoutingAssignments(): Response
    {
        $role = "scouting";

        $path = MOCK_DIR . "football/available-staff.json";
        $staff = \App\Helpers\Common::getJsonFileData($path);

        $available = array_filter($staff, function ($item) use ($role) {
            return $item['role'] == $role;
        });
        $assignments = array_map(function () {
            return [
                'assignmentId' => rand(1, 999),
                'region' => "",
                'country' => "",
                'focus' => "young_talents",
                'ageRange' => [16, 23],
                'positionGroups' => [],
                'duration' => rand(1, 30),
                'startDate' => "",
                'progress' => rand(0, 100),
                'status' => rand(0, 1) == 0 ? "active" : "completed",
            ];
        }, $available);

        return $this->json([
            'assignments' => array_values($assignments),
            'available' => array_values($available),
        ]);
    }
}
