<?php
$lastVersion = end($projectVersions);
$lastVersionKey = key($projectVersions);
?>

<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>
                    document.write(new Date().getFullYear())
                </script> © <?= $commonController->getSiteName() ?>.
            </div>
            <div class="col-sm-6">
                <a href="<?= home_url("version") ?>" class="text-sm-end d-none d-sm-block">
                    Version <?= $lastVersionKey ?>
                </a>
            </div>
        </div>
    </div>
</footer>