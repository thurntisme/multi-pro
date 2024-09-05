<?php

$currentTime = (int)date('H') * 3600 + (int)date('i') * 60 + (int)date('s');
$totalSecondsInDay = 24 * 3600;
$dayProgress = ($currentTime / $totalSecondsInDay) * 100;
$currentDay = date('j');
$dayWithSuffix = getDayWithSuffix($currentDay);
$daysInMonth = date('t');
$monthProgress = ($currentDay / $daysInMonth) * 100;
$currentMonth = date('M');
$currentYear = date('Y');
$currentM = (int)date('n');
$yearProgress = ($currentM / 12) * 100;

?>
<style>
  #datetime .progress {
    height: 40px;
    position: relative;
    background-color: transparent;
  }

  #datetime .progress .progress-text {
    font-size: 16px;
    font-weight: 600;
    position: absolute;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
</style>

<div class="card mb-4" id="datetime">
  <div class="card-body p-3">
    <div class="row align-items-center justify-content-sm-between">
      <div class="col-xl-4 col-md-6 px-0 px-2">
        <div class="progress border">
          <div class="progress-bar bg-gray-200" role="progressbar" style="width: <?= round($dayProgress, 2) ?>%;"
            aria-valuenow="<?= round($dayProgress, 2) ?>" aria-valuemin="0" aria-valuemax="100"></div>
          <div class="absolute text-gray-800 font-large progress-text"><?= $currentDay ?></div>
        </div>
      </div>
      <div class="col-xl-4 col-md-6 px-0 px-2">
        <div class="progress border">
          <div class="progress-bar bg-gray-200" role="progressbar" style="width: <?= round($monthProgress, 2) ?>%;"
            aria-valuenow="<?= round($monthProgress, 2) ?>" aria-valuemin="0" aria-valuemax="100"></div>
          <div class="absolute text-gray-800 font-large progress-text"><?= $currentMonth ?></div>
        </div>
      </div>
      <div class="col-xl-4 col-md-6 px-0 px-2">
        <div class="progress border">
          <div class="progress-bar bg-gray-200" role="progressbar" style="width: <?= round($yearProgress, 2) ?>%;"
            aria-valuenow="<?= round($yearProgress, 2) ?>" aria-valuemin="0" aria-valuemax="100"></div>
          <div class="absolute text-gray-800 font-large progress-text"><?= $currentYear ?></div>
        </div>
      </div>
    </div>
  </div>
</div>