<?php
$pageTitle = "Dashboard";

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

echo '<div class="row">';
echo '<div class="col-xl-4 col-md-6 mb-4">Hello <br/>';
echo '<a type="button" class="btn btn-secondary mt-4" href="' . home_url("projects/list") . '">Go to Project List</a>';
echo '</div></div>';

$pageContent = ob_get_clean();


ob_start();
echo '<link
      href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css"
      rel="stylesheet"
    />';
$additionCss = ob_get_clean();

include 'layout.php';
