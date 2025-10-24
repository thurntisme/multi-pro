<?php

$allMessages = App\Helpers\Flash::get();

/**
 *
 * @param string $type
 * @return string
 */
function getBootstrapAlertClass(string $type): string
{
  switch ($type) {
    case 'success':
      return 'alert-success';
    case 'error':
      return 'alert-danger';
    case 'warning':
      return 'alert-warning';
    case 'info':
      return 'alert-info';
    default:
      return 'alert-secondary';
  }
}

foreach ($allMessages as $type => $messages) {
  foreach ($messages as $msg) {
    echo '<div class="alert ' . getBootstrapAlertClass(htmlspecialchars($type)) . ' alert-dismissible fade show" role="alert">';
    echo htmlspecialchars($msg);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
  }
}
