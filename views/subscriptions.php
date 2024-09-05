<?php
$pageTitle = "Subscriptions";

require_once 'controllers/SubscriptionController.php';
$subscriptionController = new SubscriptionController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action_name'])) {
    if ($_POST['action_name'] === 'create_subscription') {
      $subscriptionController->createSubscription();
    }
    if ($_POST['action_name'] === 'delete_subscription') {
      $subscriptionController->deleteSubscription();
    }
  }
};
$subscriptionLists = $subscriptionController->listSubscriptions();

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

$currencies = [
  'USD' => 'US Dollar',
  'EUR' => 'Euro',
  'GBP' => 'British Pound',
  'JPY' => 'Japanese Yen',
  'VND' => 'Vietnamese Dong'
];
$currencyOptions = '';
foreach ($currencies as $currencyCode => $currencyName) {
  $currencyOptions .=  '<option value="' . htmlspecialchars($currencyCode) . '">' . htmlspecialchars($currencyName) . '</option>';
}
$paymentTypes = [
  'custom_date' => 'Custom Date',
  'monthly' => 'Monthly',
  'yearly' => 'Yearly'
];
$paymentTypeOptions = '';
foreach ($paymentTypes as $code => $name) {
  $paymentTypeOptions .=  '<option value="' . htmlspecialchars($code) . '">' . htmlspecialchars($name) . '</option>';
}
$dayOptions = '';
for ($day = 1; $day <= 31; $day++) {
  $dayOptions .=  '<option value="' . $day . '">' . $day . '</option>';
}
$monthOptions = '';
foreach ($months as $month_value => $month_name) {
  $monthOptions .=  '<option value="' . htmlspecialchars($month_value) . '">' . htmlspecialchars($month_name) . '</option>';
}
$yearOptions = '';
$currentYear = date('Y');
$endYear = $currentYear + 10;
for ($year = $currentYear; $year <= $endYear; $year++) {
  $yearOptions .=  '<option value="' . $year . '">' . $year . '</option>';
}

echo '<form method="POST" action="' . home_url("subscriptions") . '" class="form-inline mb-4">
          <input type="hidden" name="action_name" value="create_subscription">
          <div class="form-group mr-4">
            <label for="service_name" class="col-form-label mr-4 justify-content-start" style="width: 120px">Service Name</label>
            <div class="flex-grow-1">
              <input type="text" class="form-control" id="service_name" name="service_name">
            </div>
          </div>
          <div class="d-flex mt-3" style="min-width: 100%">
            <div class="form-group mr-4">
              <label for="payment_type" class="col-form-label mr-4 justify-content-start" style="width: 120px">Payment Type</label>
              <div class="flex-grow-1">
                <select class="form-control" class="form-control" name="payment_type" id="payment_type">'
  . $paymentTypeOptions .
  '</select>
              </div>
            </div>
            <div class="form-group mr-4">
              <label for="payment_date" class="col-form-label mr-4 justify-content-start">Payment Date</label>
              <div class="flex-grow-1">
                <select name="payment_date" id="payment_date" class="form-control">
                    ' . $dayOptions . '
                </select>
                <select name="payment_month" id="payment_month" class="form-control">
                    ' . $monthOptions . '
                </select>
                <select name="payment_year" id="payment_year" class="form-control">
                    ' . $yearOptions . '
                </select>
              </div>
            </div>
            <div class="form-group mr-4">
              <label for="amount" class="col-form-label mr-4">Amount</label>
              <div class="flex-grow-1">
                <input type="text" class="form-control" id="amount" name="amount">
              </div>
            </div>
            <div class="form-group mr-4">
              <label for="currency" class="col-form-label mr-4">Currency</label>
              <div class="flex-grow-1">
                <select class="form-control" class="form-control" name="currency" id="currency">'
  . $currencyOptions .
  '</select>
              </div>
            </div>
            <button type="submit" class="btn btn-success">Add new</button>
          </div>
        </form>';

if (count($subscriptionLists) > 0) {
  $subscriptionOptions = '';
  foreach ($subscriptionLists as $key => $subscription) {
    $subscriptionOptions .= '
      <tr>
        <th scope="row">' . $key + 1 . '</th>
        <td>' . $subscription['service_name'] . '</td>
        <td align="center">' . $paymentTypes[$subscription['payment_type']] . '</td>
        <td align="center">' . $subscription['payment_date'] . '</td>
        <td align="center">' . $subscription['amount'] . ' ' . $subscription['currency'] . '</td>
        <td align="center">
          <form method="POST" action="' . home_url("subscriptions") . '">
            <input type="hidden" name="subscription_id" value="' . $subscription['id'] . '">
            <input type="hidden" name="action_name" value="delete_subscription">
            <button type="submit" class="btn btn-sm"><i class="fas fa-trash text-danger"></i></button>
          </form>
        </td>
      </tr>
    ';
  }

  echo '<table class="table mt-5">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Service Name</th>
          <th scope="col" class="text-center">Payment Type</th>
          <th scope="col" class="text-center">Payment Date</th>
          <th scope="col" class="text-center">Amount</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        ' . $subscriptionOptions . '
      </tbody>
    </table>
  ';
}

$pageContent = ob_get_clean();


ob_start();
echo '<script src="js/pages/subscriptions.js"></script>';
$additionJs = ob_get_clean();

include 'layout.php';
