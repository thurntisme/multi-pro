<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>
                    document.write(new Date().getFullYear())
                </script> © Mercufee.
            </div>
            <div class="col-sm-6">
                <a href="<?= App\Helpers\NetworkHelper::home_url("version") ?>" class="text-sm-end d-none d-sm-block">
                    Version <?= APP_VERSION ?>
                </a>
            </div>
        </div>
    </div>
</footer>