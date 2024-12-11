<!-- Layout config Js -->
<script src="<?= home_url("assets/js/layout.js") ?>"></script>
<!-- Bootstrap Css -->
<link href="<?= home_url("assets/css/bootstrap.min.css") ?>" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="<?= home_url("assets/css/icons.min.css") ?>" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="<?= home_url("assets/css/app.min.css") ?>" rel="stylesheet" type="text/css" />

<?php
if (!empty($additionCss)) {
  echo $additionCss;
}
?>

<!-- custom Css-->
<link href="<?= home_url("assets/css/custom.css") ?>" rel="stylesheet" type="text/css" /> 