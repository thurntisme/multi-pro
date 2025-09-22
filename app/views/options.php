<?php
$pageTitle = "Dashboard Options";

require_once 'controllers/SettingController.php';

$settingController = new SettingController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settingController->setSetting($_POST);
};

$tab = $_GET['tab'] ?? array_keys(DASHBOARD_OPTIONS)[0];

$userSettings = $settingController->listSettings();

ob_start();
echo '<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />';
$additionCss = ob_get_clean();

ob_start();
echo '<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="js/pages/settings.js"></script>';
$additionJs = ob_get_clean();

ob_start();

echo '<style>
    #settings-page .form-control {
        color: #444;
    }
    #settings-page .select2 {
        max-width: 100%;
        min-width: 100%;
    }
    #settings-page .select2-selection {
        border: 1px solid #d1d3e2;
        border-radius: .35rem;
        height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
    }
    #settings-page .select2-selection .select2-selection__rendered {
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        padding: 0;
    }
    #settings-page .select2-selection .select2-selection__arrow {
        height: 100%;
    }
</style>';

echo '<div class="jumbotron bg-light" id="settings-page">
  <div class="row">
    <div class="col-6 mx-auto">';

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

echo '<ul class="nav nav-pills mb-3">';
foreach (DASHBOARD_OPTIONS as $key => $option) {
    echo '<li class="nav-item" role="presentation">
    <a class="nav-link ' . ($tab === $key ? 'active' : '') . '" href="' . home_url('options?tab=' . $key) . '">' . convertToTitleCase($key) . '</a>
  </li>';
}
echo '</ul>';

switch ($tab) {
    case 'world_clocks':
        include 'components/dashboard-world-clocks.php';
        break;
    case 'day_blocks':
        include 'components/dashboard-day-blocks.php';
        break;
    default:
        include 'components/dashboard-general.php';
        break;
}

echo '</div>
  </div>
</div>';

$pageContent = ob_get_clean();
