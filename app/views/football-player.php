<?php
ob_start();
?>

<!-- DataTables v2 + Bootstrap 5 integration -->
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<?php
$additionCss = ob_get_clean();

$cols = ["Name", "Position", "Rating", "Nationality", "Edition", "Action"];

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

<!-- Info Modals -->
<div id="infoModal" class="modal fade modal-lg" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel">Player Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <img id="playerAvatar" src="" alt="Avatar" class="img-fluid rounded mb-3">
                        <h5 id="playerName" class="fw-bold"></h5>
                    </div>

                    <div class="col-md-12">
                        <hr />
                    </div>

                    <div class="col-md-6">
                        <p><strong>Position:</strong> <span id="playerPosition"></span></p>
                        <p><strong>Rating:</strong> <span id="playerRating"></span></p>
                        <p><strong>Edition:</strong> <span id="playerEdition"></span></p>
                        <p><strong>Nationality:</strong> <span id="playerNationality"></span></p>
                        <p><strong>Birthday:</strong> <span id="playerBirthday"></span></p>
                        <p><strong>Height:</strong> <span id="playerHeight"></span> cm</p>
                        <p><strong>Weight:</strong> <span id="playerWeight"></span> kg</p>
                        <p><strong>Foot:</strong> <span id="playerFoot"></span></p>
                        <p><strong>Fitness:</strong> <span id="playerFitness"></span>%</p>
                        <p><strong>Form:</strong> <span id="playerForm"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Salary:</strong> $<span id="playerSalary"></span></p>
                        <p><strong>Contract:</strong> <span id="playerContractLength"></span> matches</p>
                        <p><strong>Market Value:</strong> $<span id="playerMarketValue"></span></p>
                        <p><strong>Potential:</strong> <span id="playerPotential"></span> ⭐</p>
                        <p><strong>Skill Moves:</strong> <span id="playerSkillMoves"></span>⭐</p>
                        <p><strong>Preferred Role:</strong> <span id="playerPreferredRole"></span></p>
                        <p><strong>Personality:</strong> <span id="playerPersonality"></span></p>
                        <p><strong>Injury Prone:</strong> <span id="playerInjuryProne"></span></p>
                        <p><strong>Consistency:</strong> <span id="playerConsistency"></span></p>
                    </div>

                    <div class="col-md-12">
                        <hr />
                    </div>

                    <div class="col-md-3">
                        <h6 class="fw-bold mt-2">⚡ Physical Attributes</h6>
                        <div id="attrPhysical"></div>
                    </div>
                    <div class="col-md-3">
                        <h6 class="fw-bold mt-3">🧠 Mental Attributes</h6>
                        <div id="attrMental"></div>
                    </div>
                    <div class="col-md-3">
                        <h6 class="fw-bold mt-3">🎯 Technical Skills</h6>
                        <div id="attrTechnical"></div>
                    </div>
                    <div class="col-md-3">
                        <h6 class="fw-bold mt-3">🧤 Goalkeeper Attributes</h6>
                        <div id="attrGoalkeeper"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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