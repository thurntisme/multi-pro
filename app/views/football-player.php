<?php
ob_start();
?>

<!-- DataTables v2 + Bootstrap 5 integration -->
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<?php
$additionCss = ob_get_clean();

$cols = ["Name", "Position", "Rating", "Edition", "Action"];

ob_start();
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Ajax Datatables</h5>
            </div>
            <div class="card-body">
                <table id="football-table" class="display table table-bordered dt-responsive" style="width:100%">
                    <thead>
                        <tr>
                            <?php foreach ($cols as $col): ?>
                                <th><?= $col ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <?php foreach ($cols as $col): ?>
                                <th><?= $col ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div><!--end col-->
</div><!--end row-->

<?php
$pageContent = ob_get_clean();

ob_start();
?>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script src="<?= App\Helpers\Network::home_url('assets/js/pages/football-player.js') ?>"></script>
<?php
$additionJs = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';