<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $data = array_merge([
            'title' => 'Dashboard'
        ], $this->getDashboardData());
        return $this->view('dashboard', $data);
    }

    private function getDashboardData()
    {
        $checklist = DEFAULT_DASHBOARD_OPTIONS['checklist'];
        $event = DEFAULT_DASHBOARD_OPTIONS['event'];
        $today = strtolower(date('D'));
        $current_time = time();
        $today_events = [];
        foreach ($event as $e) {
            if (in_array($today, $e['date'])) {
                $event_start = strtotime($e['start_time']); // Convert start time to timestamp
                $event_end = strtotime($e['end_time']); // Convert end time to timestamp

                // Check status: Passed, Upcoming, or Processing
                if ($current_time < $event_start) {
                    $status = 'Upcoming';
                } elseif ($current_time <= $event_end) {
                    $status = 'Processing';
                } else {
                    $status = 'Passed';
                }

                $today_events[] = array_merge($e, ['status' => $status]);
            }
        }
        $user_logs = [];
        $isUserCheckIn = false;

        return [
            'checklist' => $checklist,
            'events' => $today_events,
            'user_logs' => $user_logs,
            'isUserCheckIn' => $isUserCheckIn
        ];
    }
}