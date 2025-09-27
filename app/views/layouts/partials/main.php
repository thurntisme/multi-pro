<?php

$cur_lang = 'en';

$isScssConverted = false;

if ($isScssConverted) {
    $main_css = DIR_ASSETS_PATH . "css/app.css";
    $target_css = DIR_ASSETS_PATH . "css/app.min.css";

    if (!file_exists($main_css)) {
        die("Source CSS file not found: $main_css");
    }

    $css = file_get_contents($main_css);

    $css = preg_replace('!/\*.*?\*/!s', '', $css);
    $css = preg_replace('/\s+/', ' ', $css);
    $css = str_replace([' {', '{ '], '{', $css);
    $css = str_replace([' }', '} '], '}', $css);
    $css = str_replace([' ;', '; '], ';', $css);
    $css = str_replace([' :', ': '], ':', $css);
    $css = trim($css);

    file_put_contents($target_css, $css);
}

?>
<!doctype html>
<html lang="<?= $cur_lang ?>" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">