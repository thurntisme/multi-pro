<script type="text/javascript">
  const home_url = "<?= home_url("") ?>";
</script>

<!-- JAVASCRIPT -->
<script src="<?= home_url("assets/libs/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>
<script src="<?= home_url("assets/libs/simplebar/simplebar.min.js") ?>"></script>
<script src="<?= home_url("assets/libs/node-waves/waves.min.js") ?>"></script>
<script src="<?= home_url("assets/libs/feather-icons/feather.min.js") ?>"></script>
<script src="<?= home_url("assets/js/plugins.js") ?>"></script>

<?php
if (!empty($additionJs)) {
  echo $additionJs;
}
?>