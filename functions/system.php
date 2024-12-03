<?php

function get_log_message()
{
  $fileName = "assets/json/system_log.json";
  if (file_exists($fileName)) {
    return json_decode(file_get_contents($fileName), true);
  } else {
    return [];
  }
}

function isUserCheckIn()
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

function resetLogs()
{
  $fileName = "assets/json/system_log.json";

  // Save logs back to the file
  file_put_contents($fileName, json_encode([], JSON_PRETTY_PRINT));
}

function checkIn()
{
  global $user_id;
  resetLogs();
  log_message('INFO', 'User checked in.', ['user_id' => $user_id]);
}

function checkOut()
{
  $logs = get_log_message();
  var_dump($logs);
  resetLogs();
  die();
}

function log_message($level, $message, $context = [])
{
  $fileName = "assets/json/system_log.json";
  $logData = [
    'level' => $level,
    'message' => $message,
    'timestamp' => date('Y-m-d H:i:s'),
    'context' => $context,
  ];

  // Read the existing logs
  $logs = get_log_message();

  // Add the new log entry
  $logs[] = $logData;

  // Save logs back to the file
  file_put_contents($fileName, json_encode($logs, JSON_PRETTY_PRINT));
}

// Example usage
// log_message('INFO', 'User logged in.', ['user_id' => 123]);
