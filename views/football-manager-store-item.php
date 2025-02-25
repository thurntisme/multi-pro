<?php
$pageTitle = "Football Manager - Store";

require_once DIR . '/functions/generate-player.php';
$commonController = new CommonController();
$list = $commonController->convertResources(DEFAULT_STORE_ITEMS, 3);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['action_name'])) {
        if ($_POST['action_name'] === 'generate_player_on_demand') {
            $player_name = $_POST['player_name'];
            $playerData = [
                "name" => $player_name,
                "nationality" => $_POST['player_nationality'],
                "weight" => (int)$_POST['player_weight'],
                "height" => (int)$_POST['player_height'],
                "best_position" => $_POST['best_position'],
                "playable_positions" => $_POST['playable_positions'],
            ];
            $players = generateRandomPlayers('on-demand', $playerData);
            try {
                exportPlayersToJson($players);
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Player $player_name submitted successfully.";
            } catch (Exception $e) {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to submit a new player.";
            }
        }
    }
}

ob_start();
?>

<?php
include_once DIR . '/components/alert.php';
?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <?php includeFileWithVariables('components/football-player-topbar.php'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <h5 class="mb-3">Other Items</h5>
                <?php foreach ($list['resources'] as $item): ?>
                    <div class="card product">
                        <div class="card-body">
                            <div class="row gy-3 text-center">
                                <div class="col-sm-auto mx-auto">
                                    <div class="avatar-lg bg-light rounded p-1">
                                        <img src="assets/images/products/img-8.png" alt=""
                                             class="img-fluid d-block">
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <h5 class="fs-14 text-truncate"><?= $item['name'] ?></h5>
                                    <ul class="list-inline text-muted">
                                        <li class="list-inline-item"><?= $item['description'] ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- card body -->
                        <div class="card-footer">
                            <div class="row align-items-center gy-3 justify-content-center">
                                <div class="col-sm-auto">
                                    <div class="d-flex align-items-center gap-2 text-muted">
                                        <a href="apps-ecommerce-checkout.html"
                                           class="btn btn-success btn-label right ms-auto"><i
                                                    class="ri-arrow-right-line label-icon align-bottom fs-16 ms-2"></i>
                                            Take it</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end card footer -->
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-lg-9">
                <div class="card">
                    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                        <input type="hidden" name="action_name" value="generate_player_on_demand">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-center">Player on Demand</h5>
                            <p class="text-muted text-center mb-0 mt-1">Submit a player for consideration. We will
                                review your submission and publish the player once approved.</p>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div>
                                        <label for="player-name" class="form-label">Player Name <span
                                                    class="text-danger">*</span></label>
                                        <input type="text" name="player_name" class="form-control" id="player-name"
                                               placeholder="Enter Player Name" required/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div>
                                        <label for="player_nationality" class="form-label">Nationality <span
                                                    class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="player_nationality" required>
                                            <option value="">Select Nationality</option>
                                            <?php foreach (DEFAULT_NATIONALITY as $nation): ?>
                                                <option value="<?= $nation ?>"><?= $nation ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div>
                                        <label for="best_position" class="form-label">Best Position <span
                                                    class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="best_position" required>
                                            <option value="">Select Position</option>
                                            <?php foreach ($positions as $position): ?>
                                                <option value="<?= $position ?>"><?= $position ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div>
                                        <label for="playable_positions" class="form-label">Playable Positions <span
                                                    class="text-danger">*</span></label>
                                        <select class="form-control" data-choices data-choices-removeItem
                                                name="playable_positions[]" multiple required>
                                            <option value="">Select Positions</option>
                                            <?php foreach ($positions as $position): ?>
                                                <option value="<?= $position ?>"><?= $position ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div>
                                        <label for="player_weight" class="form-label">Weight (kg)<span
                                                    class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="player_weight"
                                               id="player_weight" min="60" max="110" placeholder="Weight (kg)"
                                               required/>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div>
                                        <label for="player_height" class="form-label">Height (cm)<span
                                                    class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="player_height"
                                               id="player_height" min="165" max="200" placeholder="Height (cm)"
                                               required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="hstack justify-content-center gap-2">
                                <a href="<?= home_url('football-manager/store') ?>" class="btn btn-ghost-dark"><i
                                            class="ri-close-line align-bottom"></i> Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
$pageContent = ob_get_clean();
