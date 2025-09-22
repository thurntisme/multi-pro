<?php
$pageTitle = "Devices Management";

require_once 'controllers/AuthenticationController.php';
$authenticationController = new AuthenticationController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action_name'])) {
    if ($_POST['action_name'] === 'out_device') {
      $authenticationController->deleteDevice();
    }
  }
};

$devicesLists = $authenticationController->listDevices();
$token = $commonController->getToken();

ob_start();

if (isset($_SESSION['message'])) {
  $messageType = $_SESSION['message_type'] ?? 'info';
  echo '<div class="alert alert-' . htmlspecialchars($messageType) . ' alert-dismissible fade show mb-4" role="alert">'
    . htmlspecialchars($_SESSION['message']) .
    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>';

  unset($_SESSION['message']);
  unset($_SESSION['message_type']);
}

if (count($devicesLists) > 0) {
  $deviceRows = '';
  foreach ($devicesLists as $key => $device) {
    $deviceRows .= '
      <tr>
        <th scope="row">' . $key + 1 . '</th>
        <td align="center">' . $device['device_name'] . ' ' . ($token === $device['token'] ? '<span class="text-success">(Current)</span>' : '') . '</td>
        <td align="center">' . $device['device_type'] . '</td>
        <td align="center">' . $device['ip_address'] . '</td>
        <td align="center">' . $device['last_time_login'] . '</td>
        <td align="center">
          <form method="POST" action="' . home_url("devices") . '">
            <input type="hidden" name="device_id" value="' . $device['id'] . '">
            <input type="hidden" name="action_name" value="out_device">
            <button type="submit" class="btn btn-sm"><i class="fas fa-sign-out-alt text-primary"></i></button>
          </form>
        </td>
      </tr>
    ';
  }

  echo '<table class="table mt-5">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col" class="text-center">Device Name</th>
          <th scope="col" class="text-center">Device Type</th>
          <th scope="col" class="text-center">IP Address</th>
          <th scope="col" class="text-center">Last time login</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        ' . $deviceRows . '
      </tbody>
    </table>
  ';
}

$pageContent = ob_get_clean();
