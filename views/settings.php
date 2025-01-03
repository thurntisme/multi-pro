<?php
$pageTitle = "Settings";

require_once 'controllers/SettingController.php';

$settingController = new SettingController();

$tab = $_GET['tab'] ?? 'general';
$default_settings = DEFAULT_SYSTEM_SETTINGS[$tab] ?? DEFAULT_SYSTEM_SETTINGS['general'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name']) && ($_POST['action_name'] === 'update_record')) {
        $postData = [];
        foreach ($default_settings as $item) {
            if ($item['type'] === 'checkbox') {
                $postData[$item['field']] = isset($_POST[$item['field']]) ? 1 : 0;
            } else {
                $postData[$item['field']] = $_POST[$item['field']] ?? $item['value'];
            }
        }
        $settingController->setSetting($postData);
    }
}

$settings = $settingController->listSettings();
$settingData = [];
foreach ($settings as $item) {
    $settingData[$item['option_key']] = $item['option_value'];
}

ob_start();
?>

<?php
include_once DIR . '/components/alert.php';
?>
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0">
            <?php foreach (array_keys(DEFAULT_SYSTEM_SETTINGS) as $option) { ?>
                <li class="nav-item">
                    <a class="nav-link <?= $tab === $option ? 'active' : '' ?>" href="<?= home_url("app/settings?tab=" . $option) ?>">
                        <i class="fas fa-home"></i> <?= ucwords(str_replace('_', ' ', $option)) ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
            <div class="row">
                <input type="hidden" name="action_name" value="update_record">
                <?php foreach ($default_settings as $setting) { ?>
                    <div class="<?= $setting['col'] ?>">
                        <div class="mb-3">
                            <?= generateFormControl($setting['field'], $setting['field'], $settingData[$setting['field']] ?? $setting['value'], "", $setting['type'], ucwords(str_replace('_', ' ', $setting['field'])), $setting['options'] ?? []) ?>
                        </div>
                    </div>
                <?php } ?>
                <!--end col-->
                <div class="col-lg-12 pt-3">
                    <div class="hstack gap-2 justify-content-center">
                        <button type="button" class="btn btn-light">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </form>
    </div>
</div>
<?php

$pageContent = ob_get_clean();
