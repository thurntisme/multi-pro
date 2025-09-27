<?php
$font = 'Helvetica';
$googleFontsUrl = "https://fonts.googleapis.com/css2?family=" . urlencode($font) . ":wght@100..900&display=swap";
?>
<link href="<?= $googleFontsUrl ?>" rel="stylesheet">

<!-- Layout config Js -->
<script src="<?= App\Helpers\Network::home_url("assets/js/layout.js") ?>"></script>
<!-- Bootstrap Css -->
<link href="<?= App\Helpers\Network::home_url("assets/css/bootstrap.min.css") ?>" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="<?= App\Helpers\Network::home_url("assets/css/icons.min.css") ?>" rel="stylesheet" type="text/css" />

<link href="<?= App\Helpers\Network::home_url('assets/libs/sweetalert2/sweetalert2.min.css') ?>" rel="stylesheet" />

<style>
  body {
    font-family:
      <?= $font ?>
    ;
  }

  :is(.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6) {
    font-family:
      <?= $font ?>
      !important;
  }
</style>

<!-- App Css-->
<link href="<?= App\Helpers\Network::home_url("assets/css/app.min.css") ?>" rel="stylesheet" type="text/css" />

<!-- custom Css-->
<link href="<?= App\Helpers\Network::home_url("assets/css/custom.css") ?>" rel="stylesheet" type="text/css" />

<?php
if (!empty($additionCss)) {
  echo $additionCss;
}
?>