<?php
ob_start();
?>

<h1><?= $title ?> Page</h1>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
