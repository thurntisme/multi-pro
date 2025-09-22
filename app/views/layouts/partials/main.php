<?php

$cur_lang = 'en';

use ScssPhp\ScssPhp\Compiler;

$isScssConverted = false;

if ($isScssConverted) {
    $compiler = new Compiler();

    $main_css = "assets/css/app.min.css";
    $source_scss = "assets/scss/config/default/app.scss";

    if (!file_exists($source_scss)) {
        die("Source SCSS file not found: $source_scss");
    }

    $scssContents = file_get_contents($source_scss);
    $import_path = "assets/scss/config/default";

    $compiler->addImportPath($import_path);

    try {
        // Use compileString() instead of compile()
        $css = $compiler->compileString($scssContents)->getCss();
        file_put_contents($main_css, $css);
        echo "SCSS compiled successfully to $main_css";
    } catch (\Exception $e) {
        die("SCSS compilation error: " . $e->getMessage());
    }
}

?>
<!doctype html>
<html lang="<?= $cur_lang ?>" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">