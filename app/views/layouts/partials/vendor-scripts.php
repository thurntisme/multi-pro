<script type="text/javascript">
  const home_url = "<?= App\Helpers\Network::home_url("") ?>";
</script>

<!-- JAVASCRIPT -->
<script src="<?= App\Helpers\Network::home_url("assets/libs/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>
<script src="<?= App\Helpers\Network::home_url("assets/libs/simplebar/simplebar.min.js") ?>"></script>
<script src="<?= App\Helpers\Network::home_url("assets/libs/node-waves/waves.min.js") ?>"></script>
<script src="<?= App\Helpers\Network::home_url("assets/libs/feather-icons/feather.min.js") ?>"></script>
<script src="<?= App\Helpers\Network::home_url("assets/js/plugins.js") ?>"></script>
<script src="<?= App\Helpers\Network::home_url("/assets/libs/sweetalert2/sweetalert2.min.js") ?>"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- App js -->
<script src="<?= App\Helpers\Network::home_url("assets/js/app.js") ?>"></script>
<script src="<?= App\Helpers\Network::home_url("assets/js/custom.js") ?>"></script>

<?php
if (!empty($additionJs)) {
  echo $additionJs;
}
?>