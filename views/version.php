<?php
$pageTitle = "Version";

ob_start();
?>

<div class="row">
    <div class="col-lg-12">
        <div>
            <h5 class="text-center">Version Timeline</h5>
            <div class="timeline">
                <?php
                $index = 0;
                foreach (DEFAULT_VERSION_TIMELINE as $key => $value) { ?>
                    <div class="timeline-item <?= $index % 2 == 0 ? 'left' : 'right' ?>">
                        <i class="icon ri-<?= DEFAULT_VERSION_ICONS[$index % count(DEFAULT_VERSION_ICONS)] ?>"></i>
                        <div class="date"><?= $commonController->convertDate($value['release_date']) ?></div>
                        <div class="content">
                            <div class="timeline-box">
                                <div class="timeline-text">
                                    <h5><?= $key ?></h5>
                                    <ul class="mb-0 text-muted vstack gap-2">
                                        <?php foreach ($value['features'] as $feature) { ?>
                                            <li><?= $feature ?></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    $index++;
                }
                ?>
            </div>
        </div>
    </div>
    <!--end col-->
</div>

<?php

$pageContent = ob_get_clean();

include 'layout.php';
