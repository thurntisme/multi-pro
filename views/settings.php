<?php
$pageTitle = "Settings";

require_once 'controllers/SettingController.php';

$settingController = new SettingController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settingController->setSetting($_POST);
};

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

echo '<form method="POST" action="' . home_url("settings") . '">';


function renderSettingField($field)
{
    global $userSettings;
    $key = $field['key'];
    $settingsMap = array_column($userSettings, 'value', 'key');

    $value = $settingsMap[$key] ?? $field['value'];
    if ($key === 'timezone') {
        $timezones = timezone_identifiers_list();
        $options = '';
        foreach ($timezones as $timezone) {
            $selected = $value === $timezone ? 'selected' : '';
            $options .= '<option value="' . htmlspecialchars($timezone) . '" ' . $selected . '>' .
                htmlspecialchars($timezone) .
                '</option>';
        }
        return '<select class="form-control" id="timezone" class="form-control" name="' . $key . '">'
            . $options .
            '</select>';
    } else if ($key === 'language') {
        $languages = array(
            array("en", "English"),
            array("vn", "Vietnamese"),
        );
        $options = '';
        foreach ($languages as $language) {
            $selected = $value === $language[0] ? 'selected' : '';
            $options .= '<option value="' . htmlspecialchars($language[0]) . '" ' . $selected . '>' .
                htmlspecialchars($language[1]) .
                '</option>';
        }
        return '<select class="form-control" class="form-control" name="' . $key . '">'
            . $options .
            '</select>';
    } else {
        return '<input type="text" class="form-control" id="input' . $key . '" name="' . $key . '" value="' . $value . '">';
    }
}

foreach (DEFAULT_SETTINGS as $setting) {
    echo '<div class="form-group row">
            <label for="input' . $setting['key'] . '" class="col-sm-4 col-form-label">' . convertToTitleCase($setting['key']) . '</label>
            <div class="col-sm-8">
            ' . renderSettingField($setting) . '
            </div>
        </div>';
}

echo '<div class="row mt-4">
            <div class="col-6 offset-4">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>';

echo '</form>';

echo '</div>
  </div>
</div>';

$pageContent = ob_get_clean();

include 'layout.php';
