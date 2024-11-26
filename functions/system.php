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
