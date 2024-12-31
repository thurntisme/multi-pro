<?php

$cur_lang = $systemController->getLanguage();
include_once DIR . "/assets/lang-php/" . $cur_lang . ".php";

$isScssConverted = false;

require_once("scssphp/scss.inc.php");

use ScssPhp\ScssPhp\Compiler;

if ($isScssConverted) {

    global $compiler;
    $compiler = new Compiler();

    $main_css = "assets/css/app.min.css";

    $source_scss = "assets/scss/config/default/app.scss";

    $scssContents = file_get_contents($source_scss);

    $import_path = "assets/scss/config/default";
    $compiler->addImportPath($import_path);
    $target_css = $main_css;

    $css = $compiler->compile($scssContents);

    if (!empty($css) && is_string($css)) {
        file_put_contents($target_css, $css);
    }
}
?>
<!doctype html>
<html lang="<?= $cur_lang ?>" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">