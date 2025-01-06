<?php
require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/functions/generate-player.php';

$slug = getLastSegmentFromUrl();

$footballTeamController = new FootballTeamController();
$myTeam = $footballTeamController->getMyTeam();
?>

<div class="d-flex align-items-center flex-wrap gap-2">
    <div class="flex-grow-1">
        <a class="btn btn-<?= $slug !== 'football-manager' ? 'soft-' : '' ?>info add-btn"
            href="<?= home_url("football-manager") ?>"><i
                class="ri-add-fill me-1 align-bottom"></i> Home
        </a>
        <a class="btn btn-<?= $slug !== 'my-club' ? 'soft-' : '' ?>info add-btn"
            href="<?= home_url("football-manager/my-club") ?>"><i
                class="ri-add-fill me-1 align-bottom"></i> My Club
        </a>
        <a class="btn btn-<?= $slug !== 'my-players' ? 'soft-' : '' ?>info add-btn"
            href="<?= home_url("football-manager/my-players") ?>"><i
                class="ri-add-fill me-1 align-bottom"></i> My Players
        </a>
    </div>
    <div class="flex-shrink-0">
        <div class="hstack text-nowrap gap-2">
            <a class="btn btn-<?= $slug !== 'transfer' ? 'soft-' : '' ?>info add-btn"
                href="<?= home_url("football-manager/transfer") ?>"><i
                    class="ri-add-fill me-1 align-bottom"></i> Transfer
            </a>
            <a class="btn btn-<?= $slug !== 'store' ? 'soft-' : '' ?>info add-btn"
                href="<?= home_url("football-manager/store") ?>"><i
                    class="ri-add-fill me-1 align-bottom"></i> Store
            </a>
            <?php if (!empty($myTeam['budget'])) { ?>
                <div class="ms-2 d-flex align-items-center">
                    <span class="badge bg-warning-subtle text-warning badge-border fs-20 ms-1"><?= formatCurrency($myTeam['budget'] ?? 0) ?></span>
                </div>
            <?php } ?>
        </div>
    </div>
</div>