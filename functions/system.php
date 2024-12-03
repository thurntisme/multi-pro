<?php

function get_log_badge($level): string
{
    $badge = match ($level) {
        'INFO' => 'info',
        default => 'light',
    };
    return '<span class="badge bg-primary-subtle text-' . $badge . ' badge-border me-2"><i class="mdi mdi-circle-medium"></i>' . $level . '</span>';
}

function get_all_logs()
{
    $fileName = "assets/json/system_log.json";
    if (file_exists($fileName)) {
        return json_decode(file_get_contents($fileName), true) ?? [];
    } else {
        return [];
    }
}

function get_log_message()
{
    global $user_id;
    $logs = get_all_logs();
    if (count($logs) > 0) {
        return array_filter($logs, function ($item) use ($user_id) {
            return $item['user_id'] === $user_id;
        });
    } else {
        return [];
    }
}

function isUserCheckIn(): bool
{
    $data = get_log_message();
    if (!empty($data) && count($data) > 0) {
        $timestamp = end($data)['timestamp'];

        // Convert the date string to a timestamp
        $timestamp = strtotime($timestamp);

        // Get the current date in the "Y-m-d" format
        $today = date("Y-m-d");

        // Check if the given date matches today's date
        if (date("Y-m-d", $timestamp) === $today) {
            return true;
        } else {
            return false;
        }
    }
    return false;
}

function resetLogs(): void
{
    global $user_id;
    $fileName = "assets/json/system_log.json";
    $logs = get_all_logs();
    if (count($logs) > 0) {
        $reset_data = array_filter($logs, function ($item) use ($user_id) {
            return $item['user_id'] !== $user_id;
        });
        // Save logs back to the file
        file_put_contents($fileName, json_encode(array_values($reset_data), JSON_PRETTY_PRINT));
    }
}

function checkIn(): void
{
    resetLogs();
    log_message('INFO', 'User checked in.');
}

function checkOut(): void
{
    $logs = get_log_message();
    $total_budget = array_sum(array_column($logs, 'budget'));
    var_dump($total_budget);
    resetLogs();
    die();
}

function log_message($level, $message, $context = []): void
{
    global $user_id;
    $fileName = "assets/json/system_log.json";
    $logData = [
        'level' => $level,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s'),
        'context' => $context,
        'user_id' => $user_id,
        'budget' => rand(0, 99)
    ];

    // Read the existing logs
    $logs = get_log_message();

    // Add the new log entry
    $logs[] = $logData;

    // Save logs back to the file
    file_put_contents($fileName, json_encode($logs, JSON_PRETTY_PRINT));
}
